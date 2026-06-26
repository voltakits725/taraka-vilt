<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);
        
        $now = \Carbon\Carbon::now();
        $date = $now->toDateString();

        $bookedTablesData = \App\Models\Reservation::where('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->select('table_number', 'reservation_time')
            ->get()
            ->map(function ($res) {
                return [
                    'table' => $res->table_number,
                    'time' => $res->reservation_time,
                ];
            });

        $activeOrdersData = \App\Models\Order::where('order_type', 'dine_in')
            ->whereIn('order_status', ['pending', 'processing'])
            ->whereDate('created_at', $date)
            ->select('table_number', 'created_at')
            ->get()
            ->map(function ($order) {
                return [
                    'table' => $order->table_number,
                    'time' => \Carbon\Carbon::parse($order->created_at)->format('H:i:s'),
                ];
            });

        $allBookedData = $bookedTablesData->merge($activeOrdersData)->toArray();
        
        $activeTables = \App\Models\Table::where('status', 'active')->orderBy('number')->get();

        return Inertia::render('Customer/Cart/Index', [
            'cartItems' => array_values($cartItems),
            'bookedTables' => $allBookedData,
            'activeTables' => $activeTables
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

        $menu = Menu::where('slug', $request->slug)->firstOrFail();
        $cart = session()->get('cart', []);

        // Bikin Unique Key dari kombinasi slug dan kustomisasi
        $cartKey = $menu->slug . '-' . md5($request->sugar . $request->note);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $request->qty;
        } else {
            $cart[$cartKey] = [
                'cart_key' => $cartKey, // Simpan key ini buat frontend
                'id'       => $menu->id, // Tetap sembunyi di backend
                'slug'     => $menu->slug,
                'name'     => $menu->name,
                'price'    => $menu->price,
                'image'    => $menu->image,
                'qty'      => $request->qty,
                'sugar'    => $request->sugar,
                'note'     => $request->note
            ];
        }

        session()->put('cart', $cart);
        return back();
    }

    // Fungsi Baru: Update Qty
    public function update(Request $request, $cartKey)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] = $request->qty;
            session()->put('cart', $cart);
        }
        return back();
    }

    // Fungsi Baru: Hapus Item
    public function destroy($cartKey)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        }
        return back();
    }
}