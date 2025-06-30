<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Request;
// use GuzzleHttp\Psr7\Request;

class GoogleAuthController extends Controller
{
    // Redirect user to Google login page
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle callback from Google
    public function handleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            // Jika user tidak ditemukan, buat user baru
            if (!$user) {
                return redirect('/login')->withErrors(['error' => 'Gagal login dengan Google. Silakan coba lagi.']);
            }

            // Login user
            Auth::login($user);

            // Redirect ke halaman dashboard
            return redirect('/company')->with('success', 'Berhasil login dengan Google!');

        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['error' => 'Gagal login dengan Google. Silakan coba lagi.']);
        }
    }
}
