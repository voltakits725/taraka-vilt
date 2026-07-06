<?php

namespace App\Http\Controllers\Customer\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderStatusController extends Controller
{
    public function __invoke(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.menu');

        return Inertia::render('Customer/Order/Status', [
            'order'            => $order,
            'snapToken'        => $order->snap_token,
            'midtransClientKey' => config('services.midtrans.client_key'),
        ]);
    }
}
