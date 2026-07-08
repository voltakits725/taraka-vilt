<?php

// app/Http/Controllers/Admin/AuthController.php
namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller; // Wajib di-import karena beda folder
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    // --- TAMPILAN LOGIN ---
    public function showLogin()
    {
        return Inertia::render('Admin/Auth/Login');
    }

    // --- PROSES LOGIN ---
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $userRole = Auth::user()->role;
            
            if (in_array($userRole, ['owner', 'admin', 'barista'])) {
                if (in_array($userRole, ['barista', 'admin'])) {
                    return redirect()->intended('/admin/orders');
                }
                return redirect()->intended('/admin/dashboard');
            }
            
            // Kalau pelanggan nyasar login di sini, tendang ke depan atau logout
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akses ditolak. Ini khusus admin/pegawai.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    // --- TAMPILAN REGISTER ---
    public function showRegister()
    {
        return Inertia::render('Admin/Auth/Register');
    }

    // --- PROSES REGISTER ---
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
            'role' => 'admin', 
        ]);

        Auth::login($user);

        return redirect('/admin/dashboard');
    }

    // --- PROSES LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
