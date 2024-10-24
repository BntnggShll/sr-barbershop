<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;

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
            'success'=> true
        ], 200);
    }

    // Fungsi untuk user
    public function user()
    {
        $user = Users::where('role', 'User')->get();

        return response()->json([
            'message' => 'users retrieved successfully',
            'User' => $user,
            'success'=> true
        ], 200);
    }

    // Fungsi untuk pekerja
    public function pekerja()
    {
        $data = Users::where('role', 'Pekerja')->get();

        return response()->json([
            'message' => 'pekerja users retrieved successfully',
            'data' => $data,
            'success'=> true
        ], 200);
    }

    public function destroy($id)
    {
        $Users = Users::find($id);

        if (!$Users) {
            return response()->json(['message' => 'Users not found'], 404);
        }

        $Users->delete();

        return response()->json(['message' => 'Users deleted successfully']);
    }
}
