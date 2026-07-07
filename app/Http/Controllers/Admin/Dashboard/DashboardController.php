<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Admin\Dashboard\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $data = $this->dashboardService->getAnalyticsData(
            $request->input('filter', 'weekly'),
            $request->input('start_date'),
            $request->input('end_date')
        );

        return Inertia::render('Admin/Dashboard', [
            'metrics' => $data['metrics'],
            'chart' => $data['chart'],
            'analysis' => $data['analysis'],
            'payment' => $data['payment'],
            'filters' => $data['filters'],
        ]);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->dashboardService->getAnalyticsData(
            $request->input('filter', 'weekly'),
            $request->input('start_date'),
            $request->input('end_date')
        );

        $orders = Order::with('orderItems.menu')
            ->whereBetween('created_at', [$data['startDate'], $data['endDate']])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.revenue', [
            'orders' => $orders,
            'totalRevenue' => $data['metrics']['totalRevenue'],
            'startDate' => $data['startDate'],
            'endDate' => $data['endDate'],
            'filter' => $data['filters']['current']
        ]);

        return $pdf->download('Laporan_Pendapatan_Taraka_' . $data['startDate']->format('Ymd') . '-' . $data['endDate']->format('Ymd') . '.pdf');
    }
}
