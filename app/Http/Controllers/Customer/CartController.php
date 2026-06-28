<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        return Inertia::render('Customer/Cart/Index', [
            'cartItems' => array_values(session()->get('cart', [])),
            'bookedTables' => $this->cartService->getBookedTablesData(),
            'activeTables' => $this->cartService->getActiveTables()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug'  => 'required|exists:menus,slug',
            'qty'   => 'required|integer|min:1',
            'sugar' => 'required|string',
            'note'  => 'nullable|string'
        ]);

        $this->cartService->addToCart($request->all());

        return back();
    }

    public function update(Request $request, $cartKey)
    {
        $request->validate(['qty' => 'required|integer|min:1']);
        
        $this->cartService->updateCartQuantity($cartKey, $request->qty);
        
        return back();
    }

    public function destroy($cartKey)
    {
        $this->cartService->removeFromCart($cartKey);
        
        return back();
    }
}