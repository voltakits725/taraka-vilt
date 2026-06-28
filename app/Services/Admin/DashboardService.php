<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get dashboard analytics data based on date filter
     *
     * @param string $filter
     * @param string|null $customStartDate
     * @param string|null $customEndDate
     * @return array
     */
    public function getAnalyticsData(string $filter, ?string $customStartDate = null, ?string $customEndDate = null): array
    {
        $now = Carbon::now();
        $startDate = $now->copy()->startOfDay();
        $endDate = $now->copy()->endOfDay();

        switch ($filter) {
            case 'daily':
                $startDate = $now->copy()->startOfDay();
                break;
            case 'weekly':
                $startDate = $now->copy()->subDays(6)->startOfDay();
                break;
            case 'monthly':
                $startDate = $now->copy()->subDays(29)->startOfDay();
                break;
            case 'yearly':
                $startDate = $now->copy()->subMonths(11)->startOfMonth();
                break;
            case 'custom':
                if ($customStartDate && $customEndDate) {
                    $startDate = Carbon::parse($customStartDate)->startOfDay();
                    $endDate = Carbon::parse($customEndDate)->endOfDay();
                } else {
                    $startDate = $now->copy()->subDays(6)->startOfDay();
                }
                break;
        }

        // Metrics
        $ordersInRange = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid');
        $totalOrders = $ordersInRange->count();
        $totalRevenue = $ordersInRange->sum('total_amount');
        $activeMenus = Menu::count();

        // Charts
        $chartData = [];
        $chartLabels = [];
        $diffDays = $startDate->diffInDays($endDate);
        
        if ($diffDays > 60) {
            $sales = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid')
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as date'),
                    DB::raw('SUM(total_amount) as total')
                )
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->pluck('total', 'date')
                ->toArray();

            $period = \Carbon\CarbonPeriod::create($startDate, '1 month', $endDate);
            foreach ($period as $dt) {
                $monthKey = $dt->format('Y-m');
                $chartLabels[] = $dt->translatedFormat('F Y');
                $chartData[] = $sales[$monthKey] ?? 0;
            }
        } else {
            $sales = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total_amount) as total')
                )
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->pluck('total', 'date')
                ->toArray();

            $period = \Carbon\CarbonPeriod::create($startDate, '1 day', $endDate);
            foreach ($period as $dt) {
                $dayKey = $dt->format('Y-m-d');
                $chartLabels[] = $dt->translatedFormat('d M');
                $chartData[] = $sales[$dayKey] ?? 0;
            }
        }

        // Top Entities
        $topMenus = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.payment_status', 'paid')
            ->select('menus.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('menus.id', 'menus.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $topCategories = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('categories', 'menus.category_id', '=', 'categories.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.payment_status', 'paid')
            ->select('categories.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Payment Analytics
        $allOrdersInRange = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $paidOrders = Order::whereBetween('created_at', [$startDate, $endDate])->where('payment_status', 'paid')->count();
        $pendingOrders = Order::whereBetween('created_at', [$startDate, $endDate])->where('payment_status', 'pending')->count();
        $expiredOrders = Order::whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_status', ['expired', 'failed', 'cancelled'])->count();

        $paymentMethods = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->whereNotNull('payment_type')
            ->select('payment_type', DB::raw('COUNT(*) as total'))
            ->groupBy('payment_type')
            ->get()
            ->map(function ($item) {
                $labels = [
                    'bank_transfer' => 'TF Bank Lainnya',
                    'bank_transfer_bca' => 'TF Bank BCA',
                    'bank_transfer_bni' => 'TF Bank BNI',
                    'bank_transfer_bri' => 'TF Bank BRI',
                    'bank_transfer_cimb' => 'TF Bank CIMB',
                    'bank_transfer_permata' => 'TF Bank Permata',
                    'bank_transfer_mandiri' => 'TF Bank Mandiri',
                    'qris' => 'QRIS',
                    'gopay' => 'GoPay',
                    'ovo' => 'OVO',
                    'dana' => 'DANA',
                    'shopeepay' => 'ShopeePay',
                    'credit_card' => 'Kartu Kredit',
                    'cstore' => 'Convenience Store',
                    'echannel' => 'Mandiri Bill',
                ];
                $item->label = $labels[$item->payment_type] ?? ucfirst($item->payment_type);
                return $item;
            });

        $avgOrderValue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->avg('total_amount') ?? 0;

        return [
            'metrics' => [
                'totalOrders' => $totalOrders,
                'totalRevenue' => $totalRevenue,
                'activeMenus' => $activeMenus,
            ],
            'chart' => [
                'labels' => $chartLabels,
                'data' => $chartData
            ],
            'analysis' => [
                'topMenus' => $topMenus,
                'topCategories' => $topCategories,
            ],
            'payment' => [
                'total' => $allOrdersInRange,
                'paid' => $paidOrders,
                'pending' => $pendingOrders,
                'expired' => $expiredOrders,
                'methods' => $paymentMethods,
                'avgOrderValue' => round($avgOrderValue),
            ],
            'filters' => [
                'current' => $filter,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ],
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }
}
