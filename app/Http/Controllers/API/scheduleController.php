<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedules;
use Illuminate\Queue\Worker;

class scheduleController extends Controller
{
    public function store(Request $request)
    {

        // Buat schedule baru
        $schedule = Schedules::create($request->all());
        $schedule->worker->name; 

        return response()->json([
            'message' => 'Schedule created successfully',
            'schedule' => $schedule,
            // 'worker' => $worker,
        ], 201);
    }
    // Menampilkan semua data schedule
    public function index()
    {
        $schedules = Schedules::with('worker')->get();
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


        // Update data schedule
        $schedule->update($request->all());
        $schedule->worker->name;

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
