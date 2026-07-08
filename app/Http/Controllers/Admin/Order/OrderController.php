<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * List semua pesanan — terbaru di atas
     */
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.menu'])
            ->latest()
            ->paginate(20);

        return Inertia::render('Admin/Order/Index', [
            'orders' => $orders,
        ]);
    }

    /**
     * Detail pesanan
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.menu']);

        return Inertia::render('Admin/Order/Show', [
            'order' => $order,
        ]);
    }

    /**
     * Update status pesanan (pending → processing → ready → completed)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,completed',
        ]);

        $order->update([
            'order_status' => $request->order_status,
        ]);

        // Deduct stock if order is being processed and hasn't been deducted yet
        if (in_array($request->order_status, ['processing', 'completed']) && !$order->is_stock_deducted) {
            $order->load('orderItems.menu.ingredients');
            foreach ($order->orderItems as $item) {
                if ($item->menu && $item->menu->ingredients) {
                    foreach ($item->menu->ingredients as $ingredient) {
                        $amountNeeded = $ingredient->pivot->amount * $item->quantity;
                        // Deduct stock, ensuring it doesn't go below 0 if we want to be safe
                        $ingredient->decrement('stock', $amountNeeded);
                    }
                }
            }
            $order->update(['is_stock_deducted' => true]);
        }

        if ($order->user) {
            $order->user->notify(new \App\Notifications\OrderStatusUpdated($order));
        }

        return back()->with('message', 'Status pesanan berhasil diupdate.');
    }
}
