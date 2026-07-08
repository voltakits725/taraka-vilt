<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? $request->user()->only('id', 'name', 'email', 'role', 'phone', 'avatar') : null,
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message')
            ],
            'activeTable' => function () use ($request) {
                $table = $request->session()->get('table_number');
                if (!$table) return null;

                $user = $request->user();
                if ($user) {
                    $activeCount = \App\Models\Reservation::where('user_id', $user->id)
                        ->where('table_number', $table)
                        ->where('reservation_date', \Carbon\Carbon::now()->toDateString())
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->count();

                    if ($activeCount === 0) {
                        $lastCompleted = \App\Models\Reservation::where('user_id', $user->id)
                            ->where('table_number', $table)
                            ->where('reservation_date', \Carbon\Carbon::now()->toDateString())
                            ->where('status', 'completed')
                            ->orderBy('updated_at', 'desc')
                            ->first();

                        if ($lastCompleted) {
                            $scanTime = $request->session()->get('table_scan_time', 0);
                            // Jika waktu scan QR LEBIH LAMA dari waktu reservasi selesai di-update, berarti sesinya kadaluarsa
                            if ($scanTime < $lastCompleted->updated_at->timestamp) {
                                $request->session()->forget('table_number');
                                $request->session()->forget('table_scan_time');
                                return null;
                            }
                        }
                    }
                }
                
                return $table;
            },
            'cartCount' => function () {
                $cart = session()->get('cart', []);
                // Ngitung total keseluruhan jumlah (qty) barang
                return array_sum(array_column($cart, 'qty')); 
            },
            'midtransClientKey' => config('services.midtrans.client_key'),
            'midtransSnapUrl'   => config('services.midtrans.snap_url'),
        ]);
    }
}
