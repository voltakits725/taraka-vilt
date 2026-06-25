<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $tables = \App\Models\Table::where('status', 'active')->orderBy('number')->get();
        return Inertia::render('Customer/Reservation/Index', [
            'tables' => $tables
        ]);
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $date = $request->date;
        $time = $request->time;

        // Cari reservasi di hari yang sama
        // Logikanya: booking aktif 2 jam dari waktu mulai.
        $requestedTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
        
        $bookedTables = Reservation::where('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->filter(function ($reservation) use ($requestedTime) {
                // Waktu reservasi yang sudah ada
                $existingTime = Carbon::createFromFormat('Y-m-d H:i:s', $reservation->reservation_date . ' ' . $reservation->reservation_time);
                
                // Selisih dalam menit
                $diffInMinutes = $requestedTime->diffInMinutes($existingTime, false);
                
                // Jika selisih absolut kurang dari 120 menit, berarti tabrakan (overlap)
                // Jika pas 120 menit (2 jam), maka abs(120) < 120 adalah false, jadi meja tersedia
                return abs($diffInMinutes) < 120;
            })
            ->pluck('table_number')
            ->toArray();

        // Cek juga orderan dine-in yang masih aktif (pending/processing)
        $activeOrders = \App\Models\Order::where('order_type', 'dine_in')
            ->whereIn('order_status', ['pending', 'processing'])
            ->whereDate('created_at', $date)
            ->get()
            ->filter(function ($order) use ($requestedTime) {
                $orderTime = Carbon::parse($order->created_at);
                $diffInMinutes = $requestedTime->diffInMinutes($orderTime, false);
                return abs($diffInMinutes) < 120;
            })
            ->pluck('table_number')
            ->toArray();

        $allBooked = array_values(array_unique(array_merge($bookedTables, $activeOrders)));

        return response()->json([
            'booked_tables' => $allBooked
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|integer|exists:tables,number',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|date_format:H:i',
            'guest_count' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:255',
        ]);

        // Cek lagi apakah meja sudah dibooking (untuk keamanan backend)
        $date = $request->reservation_date;
        $time = $request->reservation_time;
        $requestedTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
        
        $isConflict = Reservation::where('reservation_date', $date)
            ->where('table_number', $request->table_number)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->contains(function ($reservation) use ($requestedTime) {
                $existingTime = Carbon::createFromFormat('Y-m-d H:i:s', $reservation->reservation_date . ' ' . $reservation->reservation_time);
                $diffInMinutes = $requestedTime->diffInMinutes($existingTime, false);
                return abs($diffInMinutes) < 120;
            });

        // Cek juga di order aktif
        $isOrderConflict = \App\Models\Order::where('order_type', 'dine_in')
            ->where('table_number', $request->table_number)
            ->whereIn('order_status', ['pending', 'processing'])
            ->whereDate('created_at', $date)
            ->get()
            ->contains(function ($order) use ($requestedTime) {
                $orderTime = Carbon::parse($order->created_at);
                $diffInMinutes = $requestedTime->diffInMinutes($orderTime, false);
                return abs($diffInMinutes) < 120;
            });

        if ($isConflict || $isOrderConflict) {
            return back()->withErrors(['table_number' => 'Meja ini sedang digunakan atau sudah dibooking pada jam tersebut.']);
        }

        Reservation::create([
            'user_id' => Auth::id(),
            'table_number' => $request->table_number,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'guest_count' => $request->guest_count,
            'notes' => $request->notes,
            'status' => 'pending' // Menunggu admin acc
        ]);

        return redirect()->route('customer.reservation.history')->with('message', 'Booking berhasil diajukan! Menunggu konfirmasi admin.');
    }

    public function history()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Reservation/History', [
            'reservations' => $reservations
        ]);
    }
}
