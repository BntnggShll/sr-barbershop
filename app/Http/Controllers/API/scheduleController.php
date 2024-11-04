<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedules;

class scheduleController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'available_date' => 'required|date',
            'available_time_start' => 'required|date_format:H:i',
            'available_time_end' => 'required|date_format:H:i',
            'status' => 'required|in:Available,Booked,Unavailable',
        ]);

        // Buat schedule baru
        $schedule = Schedules::create($validated);

        return response()->json([
            'message' => 'Schedule created successfully',
            'schedule' => $schedule
        ], 201);
    }
    // Menampilkan semua data schedule
    public function index()
    {
        $schedules = Schedules::all();
        return response()->json(['schedule' => $schedules]);
    }

    // Menampilkan data schedule berdasarkan ID
    public function show($id)
    {
        $schedule = Schedules::find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        return response()->json($schedule);
    }
    public function update(Request $request, $id)
    {
        $schedule = Schedules::find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'available_date' => 'required|date',
            'available_time_start' => 'required|date_format:H:i',
            'available_time_end' => 'required|date_format:H:i',
            'status' => 'required|in:Available,Booked,Unavailable',
        ]);

        // Update data schedule
        $schedule->update($validated);

        return response()->json([
            'message' => 'Schedule updated successfully',
            'schedule' => $schedule
        ]);
    }
    public function destroy($id)
    {
        $schedule = Schedules::find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        // Hapus data schedule
        $schedule->delete();

        return response()->json(['message' => 'Schedule deleted successfully']);
    }

}
