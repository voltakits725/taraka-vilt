<?php

namespace App\Http\Controllers\Customer\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderHistoryController extends Controller
{
    public function __invoke()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.menu')
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Order/History', [
            'orders' => $orders,
        ]);
    }
}
