<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Customer\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class CheckoutController extends Controller
{
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function checkout(Request $request)
    {
        try {
            $order = $this->checkoutService->processCheckout(
                $request->all(),
                session()->get('cart', [])
            );

            session()->forget('cart');
            return redirect()->route('customer.order.status', $order->id);

        } catch (\Exception $e) {
            return back()->withErrors(['checkout' => $e->getMessage()]);
        }
    }

    public function status(Order $order)
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

    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.menu')
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Order/History', [
            'orders' => $orders,
        ]);
    }

    public function bill(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow downloading bill if paid
        if ($order->payment_status !== 'paid') {
            abort(403, 'Order is not paid yet');
        }

        $order->load('orderItems.menu');

        $pdf = Pdf::loadView('customer.orders.bill', compact('order'));
        
        return $pdf->download('E-Bill_Taraka_' . $order->midtrans_order_id . '.pdf');
    }

    public function notifications()
    {
        $user = Auth::user();
        return response()->json([
            'notifications' => $user->notifications()->take(10)->get(),
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    public function markNotificationAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
