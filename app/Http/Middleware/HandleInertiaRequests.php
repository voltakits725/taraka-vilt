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
                'user' => $request->user() ? $request->user()->only('id', 'name', 'email', 'role') : null,
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message')
            ],
            'activeTable' => fn () => $request->session()->get('table_number'),
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
