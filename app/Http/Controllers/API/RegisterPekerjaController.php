<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Validator;
class RegisterPekerjaController extends Controller
{
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
}
