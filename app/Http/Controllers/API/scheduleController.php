<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedules;
use Illuminate\Queue\Worker;
use Carbon\Carbon;
use App\Models\Users;

class scheduleController extends Controller
{
    public function store(Request $request)
    {

        $today = Carbon::today(); // Tanggal hari ini
        $day = $request->input('available_date'); // Jumlah hari perulangan
        $available_time_start = Carbon::createFromTimeString($request->available_time_start); // Waktu mulai, misalnya jam 8 pagi
        $available_time_end = Carbon::createFromTimeString($request->available_time_end); // Waktu selesai, misalnya jam 5 sore
        $workers = $request->input('worker_id'); // ID pekerja dari request
        
        for ($i = 0; $i < $day; $i++) {
            $current_date = $today->copy()->addDays($i); // Tambahkan `i` hari ke tanggal awal
            $current_time = $available_time_start->copy(); // Salin waktu mulai
        
            while ($current_time->lessThan($available_time_end)) {
                Schedules::create([
                    'worker_id' => $workers,
                    'available_date' => $current_date->toDateString(),
                    'available_time_start' => $current_time->toTimeString(),
                    'available_time_end' => $current_time->copy()->addHour()->toTimeString(),
                    'status' => 'Available',
                ]);
        
                $current_time->addHour(); // Tambahkan 1 jam ke waktu saat ini
            }
        }

        $schedules = Schedules::with('worker')->get();
        return response()->json([
            'message' => 'Availability slots created for all workers successfully',
            'schedule' => $schedules,
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
