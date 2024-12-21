<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Models\Subscriptions;

class SubscriptionController extends Controller
{
    public function store(Request $request, $id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'price' => 'required|numeric',
            'status' => 'required|in:Active,Expired',
            'description' => 'required|string|max:255',
        ]);

        // Tambahkan tanggal mulai dan akhir
        $validated['user_id'] = $user->user_id;
        $validated['start_date'] = Carbon::now();
        $validated['end_date'] = Carbon::now()->addDays(30);

        // Buat data subscription baru
        $subscription = Subscriptions::create($validated);
        $user->update([
            'subscription_status' => 'Aktif',
        ]);

        try {
            // Membuat payload JWT
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
                'exp' => Carbon::now()->addHours(2)->timestamp,// Token berlaku selama 2 jam // Token berlaku selama 2 jam
            ];

            // Generate JWT
            $jwtSecretKey = env('JWT_SECRET'); // Pastikan secret key disimpan di .env
            $token = JWT::encode($payload, $jwtSecretKey, 'HS256');

            // Kirim respons dengan token JWT
            return response()->json([
                'success' => true,
                'message' => 'User subscription created successfully',
                'token' => $token,
                'subscription' => $subscription,
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function index()
    {
        $subscriptions = Subscriptions::all();
        return response()->json($subscriptions);
    }

    public function show($id)
    {
        $subscription = Subscriptions::find($id);

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        return response()->json($subscription);
    }
    public function update(Request $request, $id)
    {
        $subscription = Subscriptions::find($id);

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'price' => 'required|numeric',
            'status' => 'required|in:Active,Expired',
        ]);

        // Update data subscription
        $subscription->update($validated);

        return response()->json([
            'message' => 'Subscription updated successfully',
            'subscription' => $subscription
        ]);
    }
    public function destroy($id)
    {
        $subscription = Subscriptions::find($id);

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        // Hapus subscription
        $subscription->delete();

        return response()->json(['message' => 'Subscription deleted successfully']);
    }

    // public function subscribe($id, Request $request)
    // {


    //     // Validasi data
    //     $validatedData = $request->validate([
    //         'subscription_status' => 'required|string|max:255',
    //         'description' => 'required'
    //     ]);

    //     // Update data user
    //     $user->update($validatedData);

    //     try {
    //         // Membuat payload JWT
    //         $payload = [
    //             'iss' => "http://localhost:3000/login", // Ganti dengan issuer Anda
    //             'sub' => $user->user_id,
    //             'name' => $user->name,
    //             'email' => $user->email,
    //             'phone_number' => $user->phone_number,
    //             'role' => $user->role,
    //             'subscription_status' => $user->subscription_status,
    //             'points' => $user->points,
    //             'google_id' => $user->google_id,
    //             'user_id' => $user->user_id,
    //             'image' => $user->image,
    //             'iat' => Carbon::now()->timestamp,
    //             'exp' => Carbon::now()->addHours(2)->timestamp,// Token berlaku selama 2 jam
    //         ];

    //         // Generate JWT
    //         $jwtSecretKey = env('JWT_SECRET'); // Pastikan secret key disimpan di .env
    //         $token = JWT::encode($payload, $jwtSecretKey, 'HS256');

    //         // Kirim respons dengan token JWT
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User subscription updated successfully',
    //             'token' => $token,
    //             'user' => $user,
    //         ], 200);

    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }



}
