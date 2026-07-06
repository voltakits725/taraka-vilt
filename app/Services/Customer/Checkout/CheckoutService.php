<?php

namespace App\Services\Customer\Checkout;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutService
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Process checkout
     * 
     * @param array $data Request data
     * @param array $cart Cart session data
     * @return Order
     * @throws \Exception
     */
    public function processCheckout(array $data, array $cart)
    {
        $orderType = $data['order_type'] ?? 'dine_in';
        $tableNumber = $orderType === 'takeaway' ? '-' : (session()->get('table_number') ?: $data['table_number']);

        if ($orderType === 'dine_in') {
            $this->validateDineIn($tableNumber, $data['reservation_time'] ?? null);
        }

        if (empty($cart)) {
            throw new \Exception('Keranjang kosong.');
        }

        $user = Auth::user();

        // Hitung total
        $subTotal = 0;
        foreach ($cart as $item) {
            $subTotal += $item['price'] * $item['qty'];
        }
        $tax = (int) round($subTotal * 0.10);
        $grandTotal = $subTotal + $tax;

        $midtransOrderId = 'TRK-' . time() . '-' . strtoupper(Str::random(4));

        $order = Order::create([
            'user_id'           => $user->id,
            'customer_name'     => $user->name,
            'order_type'        => $orderType,
            'table_number'      => $tableNumber,
            'total_amount'      => $grandTotal,
            'order_status'      => 'pending',
            'payment_status'    => 'unpaid',
            'midtrans_order_id' => $midtransOrderId,
        ]);

        if ($orderType === 'dine_in') {
            $this->autoCreateReservation($user->id, $tableNumber, $data['reservation_time']);
        }

        $midtransItems = $this->createOrderItems($order->id, $cart, $tax);

        $params = [
            'transaction_details' => [
                'order_id'     => $midtransOrderId,
                'gross_amount' => $grandTotal,
            ],
            'item_details' => $midtransItems,
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);
            return $order;
        } catch (\Exception $e) {
            $order->orderItems()->delete();
            $order->delete();
            throw new \Exception('Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    private function validateDineIn($tableNumber, $reservationTime)
    {
        if (!$reservationTime) {
            throw new \Exception('Gagal: Jam reservasi wajib dipilih.');
        }

        $now = Carbon::now();
        $requestedResTime = Carbon::createFromFormat('Y-m-d H:i:s', $now->toDateString() . ' ' . $reservationTime . ':00');

        $activeReservation = Reservation::where('table_number', $tableNumber)
            ->where('reservation_date', $now->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->first(function ($reservation) use ($requestedResTime) {
                $resTime = Carbon::createFromFormat('Y-m-d H:i:s', $reservation->reservation_date . ' ' . $reservation->reservation_time);
                return abs($requestedResTime->diffInMinutes($resTime, false)) < 120;
            });

        if ($activeReservation && $activeReservation->user_id !== Auth::id()) {
            session()->forget('table_number');

            $bookedTables = Reservation::where('reservation_date', $now->toDateString())
                ->whereIn('status', ['pending', 'confirmed'])
                ->get()
                ->filter(function ($reservation) use ($requestedResTime) {
                    $resTime = Carbon::createFromFormat('Y-m-d H:i:s', $reservation->reservation_date . ' ' . $reservation->reservation_time);
                    return abs($requestedResTime->diffInMinutes($resTime, false)) < 120;
                })
                ->pluck('table_number')
                ->toArray();
            
            $allTables = range(1, 15);
            $availableTables = array_diff($allTables, $bookedTables);
            $availableStr = implode(', ', array_slice($availableTables, 0, 8));
            if (count($availableTables) > 8) $availableStr .= ', dll';

            $resTimeFormatted = Carbon::createFromFormat('H:i:s', $activeReservation->reservation_time)->format('H:i');

            throw new \Exception("Gagal: Meja $tableNumber sedang diresevasi untuk jam $resTimeFormatted oleh orang lain. Meja kosong untuk jam {$reservationTime}: $availableStr. Silakan pilih meja lain.");
        }
    }

    private function autoCreateReservation($userId, $tableNumber, $reservationTime)
    {
        $now = Carbon::now();
        
        $existingUserRes = Reservation::where('table_number', $tableNumber)
            ->where('reservation_date', $now->toDateString())
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->first(function ($res) use ($now) {
                $resTime = Carbon::createFromFormat('Y-m-d H:i:s', $res->reservation_date . ' ' . $res->reservation_time);
                return abs($now->diffInMinutes($resTime, false)) < 120;
            });

        if (!$existingUserRes) {
            Reservation::create([
                'user_id' => $userId,
                'table_number' => $tableNumber,
                'reservation_date' => $now->toDateString(),
                'reservation_time' => $reservationTime . ':00',
                'guest_count' => 1,
                'notes' => 'Auto-booking via Cart Checkout',
                'status' => 'confirmed'
            ]);
        }
    }

    private function createOrderItems($orderId, $cart, $tax)
    {
        $midtransItems = [];
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'    => $orderId,
                'menu_id'     => $item['id'],
                'quantity'    => $item['qty'],
                'subtotal'    => $item['price'] * $item['qty'],
                'notes'       => $item['note'] ?? null,
            ]);

            $midtransItems[] = [
                'id'       => $item['slug'],
                'price'    => $item['price'],
                'quantity' => $item['qty'],
                'name'     => Str::limit($item['name'], 50),
            ];
        }

        if ($tax > 0) {
            $midtransItems[] = [
                'id'       => 'TAX-10',
                'price'    => $tax,
                'quantity' => 1,
                'name'     => 'Pajak (10%)',
            ];
        }

        return $midtransItems;
    }


}
