<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class CustomerAuthController extends Controller
{
    /**
     * Tampilkan halaman login pelanggan
     */
    public function showLogin()
    {
        return Inertia::render('Auth/CustomerLogin');
    }

    /**
     * Proses login pelanggan
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Hanya izinkan role customer
            if (Auth::user()->role === 'customer') {
                return redirect()->intended('/');
            }

            // Kalau admin nyasar login di sini, tendang
            Auth::logout();
            return back()->withErrors([
                'email' => 'Halaman ini khusus pelanggan. Silakan login di portal admin.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan halaman register pelanggan
     */
    public function showRegister()
    {
        return Inertia::render('Auth/CustomerRegister');
    }

    /**
     * Proses register pelanggan
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        Auth::login($user);

        return redirect()->intended('/');
    }

    /**
     * Proses logout pelanggan
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
