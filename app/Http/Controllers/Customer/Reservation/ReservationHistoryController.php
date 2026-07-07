<?php

namespace App\Http\Controllers\Customer\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReservationHistoryController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Customer/Reservation/History', [
            'reservations' => Reservation::where('user_id', Auth::id())
                ->orderBy('reservation_date', 'desc')
                ->orderBy('reservation_time', 'desc')
                ->paginate(10),
            'midtransClientKey' => config('services.midtrans.client_key')
        ]);
    }
}
