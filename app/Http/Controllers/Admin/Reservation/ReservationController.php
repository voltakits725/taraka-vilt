<?php

namespace App\Http\Controllers\Admin\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Notifications\ReservationStatusUpdated;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('user')
            ->orderBy('reservation_date', 'asc')
            ->orderBy('reservation_time', 'asc')
            ->paginate(20);

        return Inertia::render('Admin/Reservation/Index', [
            'reservations' => $reservations
        ]);
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $reservation->update(['status' => $request->status]);

        // Send notification to the user if they exist and the status is relevant
        if ($reservation->user && in_array($request->status, ['confirmed', 'cancelled', 'completed'])) {
            $reservation->user->notify(new ReservationStatusUpdated($reservation));
        }

        return back()->with('message', 'Status reservasi berhasil diupdate.');
    }
}
