<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Work_documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WorkDocumentationController extends Controller
{
    // Menampilkan semua dokumentasi kerja
    public function index()
    {
        $documentations = Work_documentation::all();
        foreach ($documentations as $documentation) {
            $documentation->photo_url = Storage::url($documentation->photo_url); // Mendapatkan URL akses gambar
        }
        return response()->json($documentations);
    }

    // Menyimpan dokumentasi kerja baru
    public function store(Request $request)
    {
        $request->validate([
            'worker_id' => 'required|exists:users,user_id',
            'reservation_id' => 'required|exists:reservations,reservation_id',
            'photo_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            'description' => 'nullable|string',
        ]);

        // Menyimpan gambar ke storage
        $path = $request->file('photo_url')->store('images', 'public'); // Menyimpan gambar di folder public/images

        // Membuat dokumentasi kerja baru
        $documentation = Work_documentation::create([
            'worker_id' => $request->worker_id,
            'reservation_id' => $request->reservation_id,
            'photo_url' => $path, // Menyimpan path gambar di database
            'description' => $request->description,
        ]);

        return response()->json($documentation, 201);
    }

    // Menampilkan dokumentasi kerja berdasarkan ID
    public function show($id)
    {
        $documentation = Work_documentation::findOrFail($id);
        $documentation->photo_url = Storage::url($documentation->photo_url); // Mendapatkan URL akses gambar
        return response()->json($documentation);
    }

    // Mengupdate dokumentasi kerja
    public function update(Request $request, $id)
    {
        $documentation = Work_documentation::findOrFail($id);

        $request->validate([
            'worker_id' => 'sometimes|exists:users,user_id',
            'reservation_id' => 'sometimes|exists:reservations,reservation_id',
            'photo_url' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('photo_url')) {
            // Hapus gambar lama jika ada
            Storage::delete($documentation->photo_url);
            // Menyimpan gambar baru
            $path = $request->file('photo_url')->store('images', 'public');
            $documentation->photo_url = $path; // Menyimpan path gambar baru di database
        }

        // Update kolom lainnya
        $documentation->worker_id = $request->worker_id ?? $documentation->worker_id;
        $documentation->reservation_id = $request->reservation_id ?? $documentation->reservation_id;
        $documentation->description = $request->description ?? $documentation->description;

        $documentation->save();

        return response()->json($documentation);
    }

    // Menghapus dokumentasi kerja
    public function destroy($id)
    {
        $documentation = Work_documentation::findOrFail($id);
        // Hapus gambar dari storage
        Storage::delete($documentation->photo_url);
        $documentation->delete();

        return response()->json(null, 204);
    }
}
