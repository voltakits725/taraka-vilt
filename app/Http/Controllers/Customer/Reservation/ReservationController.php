<?php

namespace App\Http\Controllers\Customer\Reservation;

use App\Http\Controllers\Controller;
use App\Services\Customer\Reservation\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        return \Inertia\Inertia::render('Customer/Reservation/Index', [
            'tables' => \App\Models\Table::where('status', 'active')->orderBy('number')->get()
        ]);
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $availability = $this->reservationService->checkAvailability($request->date, $request->time);

        return response()->json([
            'booked_tables' => $availability['booked_tables'],
            'occupied_tables' => $availability['occupied_tables']
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

        try {
            $reservation = $this->reservationService->createReservation($request->all());
            return redirect()->route('customer.reservation.history')->with('message', 'Silakan selesaikan pembayaran DP Booking Meja (Rp 20.000).');
        } catch (\Exception $e) {
            return back()->withErrors(['table_number' => $e->getMessage()]);
        }
    }

    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'new_date' => 'required|date',
            'new_time' => 'required|date_format:H:i',
        ]);

        try {
            $this->reservationService->rescheduleReservation($id, $request->new_date, $request->new_time);
            return back()->with('message', 'Jadwal reservasi berhasil diubah!');
        } catch (\Exception $e) {
            return back()->withErrors(['reschedule' => $e->getMessage()]);
        }
    }
}
