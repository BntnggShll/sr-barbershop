<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {

        $review = Review::create($request->all());

        return response()->json([
            'message' => 'Review created successfully',
            'review' => $review
        ], 201);
    }
    // Menampilkan semua data review
    public function index()
    {
        $reviews = Review::with(['user','worker','reservation'])->get();
        
        return response()->json($reviews);
    }

    // Menampilkan data review berdasarkan ID
    public function show($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        return response()->json($review);
    }
    public function update(Request $request, $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,reservation_id',
            'user_id' => 'required|exists:users,user_id',
            'worker_id' => 'required|exists:users,user_id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string',
        ]);

        // Update data review
        $review->update($validated);

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review
        ]);
    }
    public function destroy($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        // Hapus data review
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }

}
