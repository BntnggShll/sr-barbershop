<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Queue\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Stripe\ApiOperations\Update;

class UserController extends Controller
{
    // Fungsi untuk admin
    public function admin()
    {
        // Ambil semua pengguna yang memiliki role 'Admin'
        $admins = Users::where('role', 'Admin')->get();

        return response()->json([
            'message' => 'Admin users retrieved successfully',
            'admins' => $admins,
            'success' => true
        ], 200);
    }

    // Fungsi untuk user
    public function user()
    {
        $user = Users::where('role', 'User')->get();
        $data = $user->map(function ($user) {
            return [
                'email' => $user->email,
                'password' => $user->password,
                'phone_number' => $user->phone_number,
                'role' => $user->role,
                'subscription_status' => $user->subscription_status,
                'points' => $user->points,
                'google_id' => $user->google_id,
                'name' => $user->name,
                'image' => asset('storage/' . $user->image),
            ];
        });

        return response()->json([
            'message' => 'users retrieved successfully',
            'data' => $data,
            'success' => true
        ], 200);
    }

    // Fungsi untuk pekerja
    public function pekerja()
    {
        $data = Users::with('jadwal')->where('role', 'pekerja')->get();

        return response()->json([
            'message' => 'pekerja users retrieved successfully',
            'data' => $data,
            'success' => true
        ], 200);
    }

    public function destroy($user_id)
    {
        $Users = Users::find($user_id);

        if (!$Users) {
            return response()->json(['message' => 'Users not found'], 404);
        }

        $Users->delete();

        return response()->json(['message' => 'Users deleted successfully']);
    }
    public function update(Request $request, $user_id)
    {
        // Menemukan user berdasarkan user_id
        $user = Users::find($user_id);

        // Jika user tidak ditemukan, kembalikan respons error
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'success' => false
            ], 404);
        }

        $imagePath = $user->image; // Menyimpan path lama untuk fallback

        // Menghandle file upload jika ada
        if ($request->hasFile('image')) {
            if ($user->image) {
                // Menghapus file lama jika ada
                Storage::delete('public/' . $user->image);
            }

            // Menyimpan file gambar yang baru
            $imagePath = $request->file('image')->store('user_profile', 'public');
        }

        // Jika password ada dalam request, hash password tersebut
        if ($request->has('password')) {
            // Hash password baru sebelum menyimpannya
            $request->merge(['password' => hash::make($request->password)]);
        }

        // Mengupdate data user
        $user->update(array_merge(
            $request->except(['image']), // Menghindari overwrite gambar dengan data lain
            ['image' => $imagePath]
        ));


        // Membuat payload untuk token JWT
        $payload = [
            'iss' => "http://localhost:3000/login", // Ganti dengan issuer Anda
            'sub' => $user->user_id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'role' => $user->role,
            'subscription_status' => $user->subscription_status,
            'points' => $user->points,
            'google_id' => $user->google_id,
            'user_id' => $user->user_id,
            'image' => $user->image,
            'iat' => Carbon::now()->timestamp,
            'exp' => Carbon::now()->addHours(2)->timestamp,
        ];

        // Mengambil secret key dari file .env
        $jwtSecretKey = env('JWT_SECRET');

        // Membuat token JWT baru dengan secret key
        $token = JWT::encode($payload, $jwtSecretKey, 'HS256'); // HS256 adalah algoritma enkripsi

        // Mengembalikan response dengan data user yang diperbarui dan token baru
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user,
            'token' => $token // Token JWT yang baru dikirimkan
        ]);
    }

    public function points($id)
{
    // Cari data user berdasarkan ID
    $user = Users::find($id);

    // Periksa apakah user ditemukan
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found',
        ], 404);
    }

    // Tambahkan 1 ke points
    $user->increment('points', 1);
    $user->refresh();

    $payload = [
        'iss' => "http://localhost:3000/login", // Ganti dengan issuer Anda
        'sub' => $user->user_id,
        'name' => $user->name,
        'email' => $user->email,
        'phone_number' => $user->phone_number,
        'role' => $user->role,
        'subscription_status' => $user->subscription_status,
        'points' => $user->points,
        'google_id' => $user->google_id,
        'user_id' => $user->user_id,
        'image' => $user->image,
        'iat' => Carbon::now()->timestamp,
        'exp' => Carbon::now()->addHours(2)->timestamp,
    ];

    // Mengambil secret key dari file .env
    $jwtSecretKey = env('JWT_SECRET');

    // Membuat token JWT baru dengan secret key
    $token = JWT::encode($payload, $jwtSecretKey, 'HS256'); // HS256 adalah algoritma enkripsi

    // Mengembalikan response dengan data user yang diperbarui dan token baru
    return response()->json([
        'success' => true,
        'message' => 'Profile updated successfully',
        'user' => $user,
        'token' => $token // Token JWT yang baru dikirimkan
    ]);
}





}
