<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validasi input pengguna
            $validated = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:users',
                'password' => 'required|string',
                'phone_number' => 'required|string',
            ]);

            // Jika validasi gagal, kembalikan pesan error
            if ($validated->fails()) {
                return response()->json(['errors' => $validated->errors()], 422);
            }

            // Membuat user baru
            $user = Users::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password), // Hash password
                'role' => 'User',
                'subscription_status' => 'Tidak Aktif',
                'points' => 0,
            ]);

            // Payload untuk JWT dengan semua data pengguna
            $payload = [
                'iss' => "http://localhost:3000/register", // Ganti dengan issuer Anda
                'sub' => $user->user_id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'role' => $user->role,
                'user_id'=>$user->user_id,
                'subscription_status' => $user->subscription_status,
                'points' => $user->points,
                'iat' => Carbon::now()->timestamp,
                'exp' => Carbon::now()->addHours(2)->timestamp, // Token berlaku selama 2 jam
            ];

            // Generate JWT
            $jwtSecretKey = env('JWT_SECRET'); // Pastikan untuk menyimpan secret key di .env
            $token = JWT::encode($payload, $jwtSecretKey, 'HS256');

            // Kirim respons dengan token JWT
            return response()->json([
                'message' => 'User registered successfully',
                'token' => $token,
                'user' => $user
            ], 201);

        } catch (Exception $e) {
            // Menangkap error dan mengirimkan pesan error yang jelas
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function registerpekerja(Request $request)
    {
        try {
            // Validasi input pengguna
            $validated = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:users',
                'password' => 'required|string',
                'phone_number' => 'required|string',
            ]);

            // Jika validasi gagal, kembalikan pesan error
            if ($validated->fails()) {
                return response()->json(['errors' => $validated->errors()], 422);
            }

            // Membuat user baru
            $user = Users::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'role' => 'Pekerja',
                'subscription_status' => 'Tidak Aktif',
                'points' => 0,
            ]);

            // Jika berhasil, kembalikan user dan pesan berhasil
            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user
            ], 201);

        } catch (Exception $e) {
            // Menangkap error dan mengirimkan pesan error yang jelas
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()  // Mengembalikan pesan error untuk debugging
            ], 500);
        }
    }

    public function registeradmin(Request $request)
    {
        try {
            // Validasi input pengguna
            $validated = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:users',
                'password' => 'required|string',
                'phone_number' => 'required|string',
            ]);

            // Jika validasi gagal, kembalikan pesan error
            if ($validated->fails()) {
                return response()->json(['errors' => $validated->errors()], 422);
            }

            // Membuat user baru
            $user = Users::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'role' => 'Admin',
                'subscription_status' => 'Tidak Aktif',
                'points' => 0,
            ]);

            // Jika berhasil, kembalikan user dan pesan berhasil
            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user
            ], 201);

        } catch (Exception $e) {
            // Menangkap error dan mengirimkan pesan error yang jelas
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()  // Mengembalikan pesan error untuk debugging
            ], 500);
        }
    }
}
