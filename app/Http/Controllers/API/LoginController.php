<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Carbon\Carbon;

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

            if ($user && Hash::check($request->password, $user->password)) {
                // Payload untuk JWT
                $payload = [
                    'iss' => "http://localhost:3000/login", // Ganti dengan issuer Anda
                    'sub' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'role' => $user->role,
                    'subscription_status' => $user->subscription_status,
                    'points' => $user->points,
                    'google_id' => $user->google_id,
                    'image' => $user->image,
                    'iat' => Carbon::now()->timestamp,
                    'exp' => Carbon::now()->addHours(2)->timestamp, // Token berlaku selama 2 jam
                ];

                // Generate JWT
                $jwtSecretKey = env('JWT_SECRET'); // Pastikan untuk menyimpan secret key di .env
                $token = JWT::encode($payload, $jwtSecretKey, 'HS256');

                // Kirim respons dengan token JWT
                return response()->json([
                    'message' => 'Login successful',
                    'token' => $token,
                    'user' => $user,
                ]);
            } else {
                return response()->json([
                    'message' => 'User atau Password salah',
                ], 401);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
