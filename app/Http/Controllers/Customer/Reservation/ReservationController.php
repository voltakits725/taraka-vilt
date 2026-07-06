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
            'time' => 'required',
            'table_number' => 'required|integer',
        ]);

        $isAvailable = $this->reservationService->isTableAvailable(
            $request->table_number,
            $request->date,
            $request->time
        );

        return response()->json([
            'available' => $isAvailable
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
            $reservation = $this->reservationService->createReservation($request->all(), Auth::id());
            return redirect()->route('customer.reservation.history')->with('message', 'Reservasi berhasil dibuat! Silakan tunggu konfirmasi Admin.');
        } catch (\Exception $e) {
            return back()->withErrors(['table_number' => $e->getMessage()]);
        }
    }
}
