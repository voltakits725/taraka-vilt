<?php

namespace App\Http\Controllers\Customer\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderBillController extends Controller
{
    public function __invoke(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow downloading bill if paid
        if ($order->payment_status !== 'paid') {
            abort(403, 'Order is not paid yet');
        }

        $order->load('orderItems.menu');

        if (request()->query('format') === 'image') {
            return view('customer.orders.bill', [
                'order' => $order,
                'autoDownloadImage' => true
            ]);
        }

        $pdf = Pdf::loadView('customer.orders.bill', compact('order'));
        
        return $pdf->download('E-Bill_Taraka_' . $order->midtrans_order_id . '.pdf');
    }
}
