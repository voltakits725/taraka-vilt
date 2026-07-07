<?php

namespace App\Services\Customer\Reservation;

use App\Models\Reservation;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class ReservationService
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Check if a table is available at the requested date and time
     */
    public function checkAvailability(string $date, string $time, ?int $excludeReservationId = null): array
    {
        $requestedTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
        
        $query = Reservation::where('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed']);
            
        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        $activeReservations = $query->get()
            ->filter(function ($reservation) use ($requestedTime) {
                $existingTime = Carbon::createFromFormat('Y-m-d H:i:s', $reservation->reservation_date . ' ' . $reservation->reservation_time);
                $diffInMinutes = $requestedTime->diffInMinutes($existingTime, false);
                return abs($diffInMinutes) < 120;
            });

        $bookedTables = [];
        $occupiedTables = [];

        foreach ($activeReservations as $res) {
            if ($res->notes === 'Auto-booking via Cart Checkout') {
                $occupiedTables[] = $res->table_number;
            } else {
                $bookedTables[] = $res->table_number;
            }
        }

        return [
            'booked_tables' => array_values(array_unique(array_map('intval', $bookedTables))),
            'occupied_tables' => array_values(array_unique(array_map('intval', $occupiedTables)))
        ];
    }

    /**
     * Create a new reservation after validating conflicts
     */
    public function createReservation(array $data)
    {
        $date = $data['reservation_date'];
        $time = $data['reservation_time'];
        $requestedTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
        
        $isConflict = Reservation::where('reservation_date', $date)
            ->where('table_number', $data['table_number'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->contains(function ($reservation) use ($requestedTime) {
                $existingTime = Carbon::createFromFormat('Y-m-d H:i:s', $reservation->reservation_date . ' ' . $reservation->reservation_time);
                $diffInMinutes = $requestedTime->diffInMinutes($existingTime, false);
                return abs($diffInMinutes) < 120;
            });

        if ($isConflict) {
            throw new \Exception('Meja ini sedang digunakan atau sudah dibooking pada jam tersebut.');
        }

        $user = Auth::user();
        $midtransOrderId = 'RES-' . time() . '-' . strtoupper(Str::random(4));
        $bookingFee = 20000;

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'table_number' => $data['table_number'],
            'reservation_date' => $data['reservation_date'],
            'reservation_time' => $data['reservation_time'],
            'guest_count' => $data['guest_count'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending', // Menunggu pembayaran
            'midtrans_order_id' => $midtransOrderId,
            'payment_status' => 'unpaid'
        ]);

        $params = [
            'transaction_details' => [
                'order_id'     => $midtransOrderId,
                'gross_amount' => $bookingFee,
            ],
            'item_details' => [
                [
                    'id'       => 'RES-TBL',
                    'price'    => $bookingFee,
                    'quantity' => 1,
                    'name'     => 'Biaya Booking Meja ' . $data['table_number'],
                ]
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $reservation->update(['snap_token' => $snapToken]);
            return $reservation;
        } catch (\Exception $e) {
            $reservation->delete();
            throw new \Exception('Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Reschedule an existing reservation
     */
    public function rescheduleReservation($id, $newDate, $newTime)
    {
        $reservation = Reservation::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($reservation->status !== 'confirmed') {
            throw new \Exception('Hanya reservasi yang sudah dikonfirmasi (Lunas) yang bisa diubah jadwalnya.');
        }

        // Cek konflik di tanggal/waktu baru, kecualikan reservasi ini sendiri
        $requestedTime = Carbon::createFromFormat('Y-m-d H:i', $newDate . ' ' . $newTime);
        $isConflict = Reservation::where('reservation_date', $newDate)
            ->where('table_number', $reservation->table_number)
            ->where('id', '!=', $reservation->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->contains(function ($res) use ($requestedTime) {
                $existingTime = Carbon::createFromFormat('Y-m-d H:i:s', $res->reservation_date . ' ' . $res->reservation_time);
                $diffInMinutes = $requestedTime->diffInMinutes($existingTime, false);
                return abs($diffInMinutes) < 120;
            });

        if ($isConflict) {
            throw new \Exception('Meja ' . $reservation->table_number . ' sudah dibooking orang lain pada tanggal/jam tersebut.');
        }

        $reservation->reservation_date = $newDate;
        $reservation->reservation_time = $newTime;
        $reservation->save();

        return $reservation;
    }

    /**
     * Get reservation history for the logged in user
     */
    public function getHistory()
    {
        return Reservation::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
    }
}
