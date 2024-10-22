<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
class LoginController extends Controller
{

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = Users::where('email', $validated['email'])->first();

            // Jika pengguna ada dan password sesuai
            if ($user && Hash::check($request->password, $user->password)) {

                // Generate token (untuk autentikasi menggunakan Sanctum)
                $token = $user->createToken('login-token')->plainTextToken;

                // Kirim respons dengan token
                return response()->json([
                    'message' => 'Login successful',
                    'token' => $token,
                    'user' => $user,
                ]);
            } else {
                // Kirim respons jika user tidak ditemukan atau password salah
                return response()->json([
                    'message' => 'User atau Password salah',
                ], 401);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
