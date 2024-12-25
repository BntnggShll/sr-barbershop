<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedules;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Reservations;
use Schedule;

class ReservationsController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'service_id' => 'required|exists:services,service_id',
            'worker_id' => 'required|exists:users,user_id',
            'schedule_id' => 'required|exists:schedules,schedule_id',
        ]);
        $validated['reservation_status'] = 'Confirmed';

        $reservation = Reservations::create($validated);

        // Ambil data schedule berdasarkan schedule_id yang divalidasi
        $schedule = Schedules::find($validated['schedule_id']);

        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        // Gabungkan tanggal dan waktu menjadi objek Carbon
        $availableDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $schedule->available_date . ' ' . $schedule->available_time_start
        );

        // Ambil waktu saat ini
        $currentTime = now();

        // Hitung selisih waktu dalam menit
        $differenceInMinutes = $availableDateTime->diffInMinutes($currentTime, false);

        // Hitung estimasi dalam jam
        $differenceInHours = (int) abs($differenceInMinutes / 60);

        // Tentukan estimasi
        $estimasi = $differenceInMinutes > 0
            ? "{$differenceInMinutes} menit ({$differenceInHours} jam) dari sekarang"
            : abs($differenceInMinutes) . " menit (" . abs($differenceInHours) . " jam) yang lalu";

        // Ambil data reservations berdasarkan user_id
        $reservations = Reservations::with(['service', 'user', 'worker', 'schedule'])
            ->where('user_id', $validated['user_id'])
            ->get();

        // Tambahkan estimasi ke setiap reservation
        $reservations = $reservations->map(function ($reservation) use ($estimasi) {
            $reservation->estimasi = $estimasi;
            return $reservation;
        });

        return response()->json([
            'message' => 'Reservation created successfully',
            'reservations' => $reservations,
        ], 201);
    }

    // Menampilkan semua data reservasi
    public function index()
    {
        // Ambil semua jadwal terkait
        $schedules = Schedules::all();

        

        // Ambil semua reservasi berdasarkan user_id dengan relasi
        $reservations = Reservations::with(['service', 'user', 'worker', 'schedule'])
            ->get();

        // Tambahkan estimasi ke setiap reservasi
        $reservations = $reservations->map(function ($reservation) use ($schedules) {
            // Cari jadwal yang cocok dengan schedule_id pada reservasi
            $schedule = $schedules->firstWhere('schedule_id', $reservation->schedule_id);

            if ($schedule) {
                // Gabungkan tanggal dan waktu menjadi objek Carbon
                $availableDateTime = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $schedule->available_date . ' ' . $schedule->available_time_start
                );

                // Hitung selisih waktu dalam menit
                $currentTime = now();
                $differenceInMinutes = $availableDateTime->diffInMinutes($currentTime, false);
                $differenceInHours = (int) abs($differenceInMinutes / 60);

                // Tentukan estimasi
                $reservation->estimasi = $differenceInMinutes > 0
                    ? "{$differenceInMinutes} menit ({$differenceInHours} jam) dari sekarang"
                    : abs($differenceInMinutes) . " menit (" . abs($differenceInHours) . " jam) yang lalu";
            } else {
                $reservation->estimasi = "Jadwal tidak ditemukan";
            }

            return $reservation;
        });

        $reservations = $reservations->map(function ($reservations) {
            $reservations->formatted_date = Carbon::parse($reservations->updated_at)->format('M d Y H:i');
            return $reservations;
        });
        // Mengembalikan data dalam format JSON
        return response()->json(['data' => $reservations]);
    }


    // Menampilkan data reservasi berdasarkan ID
    public function show($user_id)
    {
        // Ambil semua jadwal terkait
        $schedules = Schedules::all();

        // Ambil semua reservasi berdasarkan user_id dengan relasi
        $reservations = Reservations::with(['service', 'user', 'worker', 'schedule'])
            ->where('user_id', $user_id)
            ->get();

        // Tambahkan estimasi ke setiap reservasi
        $reservations = $reservations->map(function ($reservation) use ($schedules) {
            // Cari jadwal yang cocok dengan schedule_id pada reservasi
            $schedule = $schedules->firstWhere('schedule_id', $reservation->schedule_id);

            if ($schedule) {
                // Gabungkan tanggal dan waktu menjadi objek Carbon
                $availableDateTime = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $schedule->available_date . ' ' . $schedule->available_time_start
                );

                // Hitung selisih waktu dalam menit
                $currentTime = now();
                $differenceInMinutes = $availableDateTime->diffInMinutes($currentTime, false);
                $differenceInHours = (int) abs($differenceInMinutes / 60);

                // Tentukan estimasi
                $reservation->estimasi = $differenceInMinutes > 0
                    ? "{$differenceInMinutes} menit ({$differenceInHours} jam) dari sekarang"
                    : abs($differenceInMinutes) . " menit (" . abs($differenceInHours) . " jam) yang lalu";
            } else {
                $reservation->estimasi = "Jadwal tidak ditemukan";
            }

            return $reservation;
        });

        // Kembalikan respons JSON
        return response()->json([
            'reservations' => $reservations,
        ], 200);
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

    public function rating ($id)
    {
        $reservation = Reservations::find($id);

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found',
            ], 404);
        }
    
        // Perbarui status reservasi menjadi "Rating"
        $reservation->update(['reservation_status' => 'Rating']);

    }

}
