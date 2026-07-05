<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Services\Customer\CustomerReservationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(CustomerReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        return Inertia::render('Customer/Reservation/Index', [
            'tables' => Table::where('status', 'active')->orderBy('number')->get()
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
            $this->reservationService->createReservation($request->all());
            return redirect()->route('customer.reservation.history')->with('message', 'Booking berhasil diajukan! Menunggu konfirmasi admin.');
        } catch (\Exception $e) {
            return back()->withErrors(['table_number' => $e->getMessage()]);
        }
    }

    public function history()
    {
        return Inertia::render('Customer/Reservation/History', [
            'reservations' => $this->reservationService->getHistory()
        ]);
    }
}
