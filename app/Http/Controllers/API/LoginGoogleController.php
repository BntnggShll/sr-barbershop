<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginGoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
     function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Periksa apakah pengguna sudah terdaftar berdasarkan email
            $user = Users::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // Buat pengguna baru jika belum terdaftar
                $user = Users::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Hash::random(16)), // Buat password acak
                    
                ]);
                Auth::login($user);
            }

            // Login pengguna
            Auth::login($user);
            $token = Auth::user()->createToken('authToken')->plainTextToken;
            return redirect('http://localhost:3000/login?token=' . $token);

        } catch (\Exception $e) {
            return redirect('http://localhost:3000/login')->withErrors('Gagal login dengan Google.');
        }
    }
}
