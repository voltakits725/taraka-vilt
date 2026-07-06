<?php

namespace App\Services\Customer\Cart;

use App\Models\Menu;
use App\Models\Reservation;
use App\Models\Order;
use App\Models\Table;
use Carbon\Carbon;

class CartService
{
    /**
     * Get all currently booked tables for the current date
     */
    public function getBookedTablesData(): array
    {
        $date = Carbon::now()->toDateString();

        $bookedTablesData = Reservation::where('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->select('table_number', 'reservation_time')
            ->get()
            ->map(function ($res) {
                return [
                    'table' => $res->table_number,
                    'time' => $res->reservation_time,
                ];
            });

        $activeOrdersData = Order::where('order_type', 'dine_in')
            ->whereIn('order_status', ['pending', 'processing'])
            ->whereDate('created_at', $date)
            ->select('table_number', 'created_at')
            ->get()
            ->map(function ($order) {
                return [
                    'table' => $order->table_number,
                    'time' => Carbon::parse($order->created_at)->format('H:i:s'),
                ];
            });

        return $bookedTablesData->toBase()->merge($activeOrdersData->toBase())->toArray();
    }

    /**
     * Get all active tables
     */
    public function getActiveTables()
    {
        return Table::where('status', 'active')->orderBy('number')->get();
    }

    /**
     * Add item to session cart
     */
    public function addToCart(array $data)
    {
        $menu = Menu::where('slug', $data['slug'])->firstOrFail();
        $cart = session()->get('cart', []);

        $cartKey = $menu->slug . '-' . md5($data['note'] ?? '');

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $data['qty'];
        } else {
            $cart[$cartKey] = [
                'cart_key' => $cartKey,
                'id'       => $menu->id,
                'slug'     => $menu->slug,
                'name'     => $menu->name,
                'price'    => $menu->price,
                'image'    => $menu->image,
                'qty'      => $data['qty'],
                'sugar'    => 'normal',
                'note'     => $data['note'] ?? null
            ];
        }

        session()->put('cart', $cart);
    }

    /**
     * Update item quantity in session cart
     */
    public function updateCartQuantity(string $cartKey, int $qty)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] = $qty;
            session()->put('cart', $cart);
        }
    }

    /**
     * Remove item from session cart
     */
    public function removeFromCart(string $cartKey)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        }
    }
}
