<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'weekly');
        $now = Carbon::now();
        
        $startDate = $now->copy()->startOfDay();
        $endDate = $now->copy()->endOfDay();

        switch ($filter) {
            case 'daily':
                $startDate = $now->copy()->startOfDay();
                break;
            case 'weekly':
                $startDate = $now->copy()->subDays(6)->startOfDay(); // Last 7 days
                break;
            case 'monthly':
                $startDate = $now->copy()->subDays(29)->startOfDay(); // Last 30 days
                break;
            case 'yearly':
                $startDate = $now->copy()->subMonths(11)->startOfMonth(); // Last 12 months
                break;
            case 'custom':
                if ($request->has('start_date') && $request->has('end_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                } else {
                    $startDate = $now->copy()->subDays(6)->startOfDay();
                }
                break;
        }

        // Get total orders & revenue in range (Only paid/successful orders)
        $ordersInRange = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid');

        $totalOrders = $ordersInRange->count();
        $totalRevenue = $ordersInRange->sum('total_amount');

        // Active Menus (Static metric)
        $activeMenus = Menu::count();

        // Chart Data Preparation
        $chartData = [];
        $chartLabels = [];

        // If the range is very large (> 60 days), group by month
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

            // Fill empty months
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

            // Fill empty days
            $period = \Carbon\CarbonPeriod::create($startDate, '1 day', $endDate);
            foreach ($period as $dt) {
                $dayKey = $dt->format('Y-m-d');
                $chartLabels[] = $dt->translatedFormat('d M');
                $chartData[] = $sales[$dayKey] ?? 0;
            }
        }

        // Top 5 Menus (Terlaris)
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

        // Top 5 Categories (Terlaris)
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
        // All orders in range (not just paid) for status ratio
        $allOrdersInRange = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $paidOrders = Order::whereBetween('created_at', [$startDate, $endDate])->where('payment_status', 'paid')->count();
        $pendingOrders = Order::whereBetween('created_at', [$startDate, $endDate])->where('payment_status', 'pending')->count();
        $expiredOrders = Order::whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_status', ['expired', 'failed', 'cancelled'])->count();

        // Payment Method Distribution
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

        // Average Order Value (paid only)
        $avgOrderValue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->avg('total_amount') ?? 0;

        return Inertia::render('Admin/Dashboard', [
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
            ]
        ]);
    }

    public function exportPdf(Request $request)
    {
        $filter = $request->input('filter', 'weekly');
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
                if ($request->has('start_date') && $request->has('end_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                } else {
                    $startDate = $now->copy()->subDays(6)->startOfDay();
                }
                break;
        }

        $orders = Order::with('orderItems.menu')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $orders->sum('total_amount');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.revenue', [
            'orders' => $orders,
            'totalRevenue' => $totalRevenue,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'filter' => $filter
        ]);

        return $pdf->download('Laporan_Pendapatan_Taraka_' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf');
    }
}
