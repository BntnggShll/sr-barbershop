<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriptions;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'price' => 'required|numeric',
            'status' => 'required|in:Active,Expired',
        ]);

        // Buat data subscription baru
        $subscription = Subscriptions::create($validated);

        return response()->json([
            'message' => 'Subscription created successfully',
            'subscription' => $subscription
        ], 201);
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



}
