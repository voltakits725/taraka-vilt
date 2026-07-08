<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user dengan google_id ini sudah ada
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                Auth::login($user);
                return redirect()->intended('/profil');
            }

            // Jika belum ada google_id, cek apakah emailnya sudah terdaftar
            $userByEmail = User::where('email', $googleUser->getEmail())->first();

            if ($userByEmail) {
                // Link akun google ke user yang sudah ada
                $userByEmail->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar()
                ]);
                Auth::login($userByEmail);
                return redirect()->intended('/profil');
            }

            // Jika benar-benar user baru
            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => null, // Password nullable di migration
                'role' => 'customer'
            ]);

            Auth::login($newUser);
            return redirect()->intended('/profil');

        } catch (\Exception $e) {
            return redirect('/masuk')->with('error', 'Gagal login menggunakan Google. Silakan coba lagi.');
        }
    }
}
