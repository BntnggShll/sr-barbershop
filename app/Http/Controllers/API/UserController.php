<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Queue\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $user = Users::find($user_id);

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
                Storage::delete('public/' . $user->image); // Hapus file lama
            }

            $imagePath = $request->file('image')->store('user_profile', 'public');
        }

        // Update data user
        $user->update(array_merge(
            $request->all(),
            ['image' => $imagePath]
        ));

        // Menyusun URL lengkap untuk gambar
        $user->image = $imagePath ? asset('storage/' . $imagePath) : null;

        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }

    



}
