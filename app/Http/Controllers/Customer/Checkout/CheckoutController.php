<?php

namespace App\Http\Controllers\Customer\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Customer\Checkout\CheckoutService;
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
}