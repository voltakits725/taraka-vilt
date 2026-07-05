<?php

namespace App\Services\Customer;

use App\Models\Reservation;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CustomerReservationService
{
    /**
     * Check if a table is available at the requested date and time
     */
    public function checkAvailability(string $date, string $time): array
    {
        $requestedTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
        
        $activeReservations = Reservation::where('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
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

        return Reservation::create([
            'user_id' => Auth::id(),
            'table_number' => $data['table_number'],
            'reservation_date' => $data['reservation_date'],
            'reservation_time' => $data['reservation_time'],
            'guest_count' => $data['guest_count'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending'
        ]);
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
