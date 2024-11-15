<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservations;

class ReservationsController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'service_id' => 'required|exists:services,service_id',
            'worker_id' => 'required|exists:users,user_id',
            'schedule_id' => 'required|exists:schedules,schedule_id',
            'reservation_status' => 'required|in:Pending,Confirmed,Completed,Canceled',
        ]);

        // Buat data reservasi baru
        $reservation = Reservations::create($validated);

        return response()->json([
            'message' => 'Reservation created successfully',
            'reservation' => $reservation
        ], 201);
    }
    // Menampilkan semua data reservasi
    public function index()
    {
        $reservations = Reservations::with('service')->get();

        // Mengembalikan data dalam format JSON
        return response()->json(['data' => $reservations]);
    }


    // Menampilkan data reservasi berdasarkan ID
    public function show($id)
    {
        $reservation = Reservations::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        return response()->json($reservation);
    }
    public function update(Request $request, $id)
    {
        $reservation = Reservations::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'service_id' => 'required|exists:services,service_id',
            'worker_id' => 'required|exists:users,user_id',
            'schedule_id' => 'required|exists:schedules,schedule_id',
            'reservation_status' => 'required|in:Pending,Confirmed,Completed,Canceled',
        ]);

        // Update data reservasi
        $reservation->update($validated);

        return response()->json([
            'message' => 'Reservation updated successfully',
            'reservation' => $reservation
        ]);
    }
    public function destroy($id)
    {
        $reservation = Reservations::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        // Hapus data reservasi
        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully']);
    }

}
