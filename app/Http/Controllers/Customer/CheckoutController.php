<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Proses checkout: buat order + request snap token dari Midtrans
     */
    public function checkout(Request $request)
    {
        // Ambil table_number dari session, atau dari request (jika pilih manual)
        $tableNumber = session()->get('table_number') ?: $request->table_number;
        $orderType = $tableNumber ? 'dine_in' : 'takeaway';

        if ($orderType === 'dine_in') {
            $now = \Carbon\Carbon::now();
            
            $activeReservation = \App\Models\Reservation::where('table_number', $tableNumber)
                ->where('reservation_date', $now->toDateString())
                ->whereIn('status', ['pending', 'confirmed'])
                ->get()
                ->first(function ($reservation) use ($now) {
                    $resTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $reservation->reservation_date . ' ' . $reservation->reservation_time);
                    return abs($now->diffInMinutes($resTime, false)) < 120;
                });

            // Jika ada yang booking, dan yang booking BUKAN user ini, tolak pesanan.
            if ($activeReservation && $activeReservation->user_id !== \Illuminate\Support\Facades\Auth::id()) {
                // Hapus session table_number agar dropdown meja muncul lagi di frontend
                $request->session()->forget('table_number');

                // Cari meja apa saja yang masih kosong saat ini
                $bookedTables = \App\Models\Reservation::where('reservation_date', $now->toDateString())
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->get()
                    ->filter(function ($reservation) use ($now) {
                        $resTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $reservation->reservation_date . ' ' . $reservation->reservation_time);
                        return abs($now->diffInMinutes($resTime, false)) < 120;
                    })
                    ->pluck('table_number')
                    ->toArray();
                
                $allTables = range(1, 15);
                $availableTables = array_diff($allTables, $bookedTables);
                $availableStr = implode(', ', array_slice($availableTables, 0, 8));
                if (count($availableTables) > 8) $availableStr .= ', dll';

                $resTimeFormatted = \Carbon\Carbon::createFromFormat('H:i:s', $activeReservation->reservation_time)->format('H:i');

                return back()->withErrors(['checkout' => "Gagal: Meja $tableNumber sedang diresevasi untuk jam $resTimeFormatted oleh orang lain. Meja kosong saat ini: $availableStr. Silakan pilih meja lain."]);
            }
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Keranjang kosong.']);
        }

        $user = Auth::user();

        // Hitung total
        $subTotal = 0;
        foreach ($cart as $item) {
            $subTotal += $item['price'] * $item['qty'];
        }
        $tax = (int) round($subTotal * 0.10);
        $grandTotal = $subTotal + $tax;

        // Generate unique order ID untuk Midtrans
        $midtransOrderId = 'TRK-' . time() . '-' . strtoupper(Str::random(4));

        // Buat Order di database
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

        // Jika Dine In, otomatis buatkan record Reservation agar meja tersebut ke-block di sistem Booking Online
        if ($orderType === 'dine_in') {
            $now = \Carbon\Carbon::now();
            
            // Cek apakah user ini sudah punya reservasi di jam ini (kalau dia scan QR sesuai bookingannya)
            $existingUserRes = \App\Models\Reservation::where('table_number', $tableNumber)
                ->where('reservation_date', $now->toDateString())
                ->where('user_id', $user->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->get()
                ->first(function ($res) use ($now) {
                    $resTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $res->reservation_date . ' ' . $res->reservation_time);
                    return abs($now->diffInMinutes($resTime, false)) < 120;
                });

            // Jika belum punya (dia murni walk-in tiba-tiba scan QR), buatkan otomatis!
            if (!$existingUserRes) {
                // Cari slot jam terdekat (kelipatan 2 jam: 15, 17, 19, 21)
                $currentHour = $now->hour;
                $slotHour = 15;
                if ($currentHour >= 17 && $currentHour < 19) $slotHour = 17;
                elseif ($currentHour >= 19 && $currentHour < 21) $slotHour = 19;
                elseif ($currentHour >= 21) $slotHour = 21;

                \App\Models\Reservation::create([
                    'user_id' => $user->id,
                    'table_number' => $tableNumber,
                    'reservation_date' => $now->toDateString(),
                    'reservation_time' => "$slotHour:00:00",
                    'guest_count' => 1,
                    'notes' => 'Auto-booking via QR Code (Walk-in)',
                    'status' => 'confirmed' // Langsung confirmed karena dia udah di tempat
                ]);
            }
        }

        // Buat Order Items
        $midtransItems = [];
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'    => $order->id,
                'menu_id'     => $item['id'],
                'quantity'    => $item['qty'],
                'subtotal'    => $item['price'] * $item['qty'],
                'sugar_level' => $this->mapSugarLevel($item['sugar'] ?? 'normal'),
                'notes'       => $item['note'] ?? null,
            ]);

            $midtransItems[] = [
                'id'       => $item['slug'],
                'price'    => $item['price'],
                'quantity' => $item['qty'],
                'name'     => Str::limit($item['name'], 50),
            ];
        }

        // Tambah pajak sebagai item terpisah
        if ($tax > 0) {
            $midtransItems[] = [
                'id'       => 'TAX-10',
                'price'    => $tax,
                'quantity' => 1,
                'name'     => 'Pajak (10%)',
            ];
        }

        // Parameter Midtrans Snap
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

            // Simpan snap_token di order
            $order->update(['snap_token' => $snapToken]);

            // Kosongkan cart
            session()->forget('cart');

            return redirect()->route('customer.order.status', $order->id);
        } catch (\Exception $e) {
            // Kalau gagal generate snap token, hapus order yang baru dibuat
            $order->orderItems()->delete();
            $order->delete();

            return back()->withErrors(['checkout' => 'Gagal memproses pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Halaman status order — pelanggan lihat status + bayar via Snap
     */
    public function status(Order $order)
    {
        // Pastikan hanya pemilik order yang bisa lihat
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

    /**
     * Map sugar level dari frontend ke enum di database
     */
    private function mapSugarLevel(string $sugar): string
    {
        return match (strtolower($sugar)) {
            'less', 'less sugar'  => 'less',
            'no sugar', 'no_sugar' => 'no_sugar',
            default                => 'normal',
        };
    }

    /**
     * Halaman Riwayat Pesanan
     */
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

    /**
     * API Fetch Notifikasi Pelanggan
     */
    public function notifications()
    {
        $user = Auth::user();
        return response()->json([
            'notifications' => $user->notifications()->take(10)->get(),
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Tandai Notifikasi sudah dibaca
     */
    public function markNotificationAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['success' => true]);
    }
}
