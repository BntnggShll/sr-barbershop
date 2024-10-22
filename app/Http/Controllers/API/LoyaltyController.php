<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loyality;

class LoyaltyController extends Controller
{
    public function store(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'user_id' => 'required|exists:users,user_id',
        'points_earned' => 'required|integer',
        'points_redeemed' => 'nullable|integer',
        'discount_percentage' => 'nullable|integer',
    ]);

    // Buat data loyalty baru
    $loyalty = Loyality::create($validated);

    return response()->json([
        'message' => 'Loyalty record created successfully',
        'loyalty' => $loyalty
    ], 201);
}
// Menampilkan semua data loyalty
public function index()
{
    $loyalties = Loyality::all();
    return response()->json($loyalties);
}

// Menampilkan data loyalty berdasarkan ID
public function show($id)
{
    $loyalty = Loyality::find($id);

    if (!$loyalty) {
        return response()->json(['message' => 'Loyalty record not found'], 404);
    }

    return response()->json($loyalty);
}
public function update(Request $request, $id)
{
    $loyalty = Loyality::find($id);

    if (!$loyalty) {
        return response()->json(['message' => 'Loyalty record not found'], 404);
    }

    // Validasi input
    $validated = $request->validate([
        'user_id' => 'required|exists:users,user_id',
        'points_earned' => 'required|integer',
        'points_redeemed' => 'nullable|integer',
        'discount_percentage' => 'nullable|integer',
    ]);

    // Update data loyalty
    $loyalty->update($validated);

    return response()->json([
        'message' => 'Loyalty record updated successfully',
        'loyalty' => $loyalty
    ]);
}
public function destroy($id)
{
    $loyalty = Loyality::find($id);

    if (!$loyalty) {
        return response()->json(['message' => 'Loyalty record not found'], 404);
    }

    // Hapus data loyalty
    $loyalty->delete();

    return response()->json(['message' => 'Loyalty record deleted successfully']);
}

}
