<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services;
use Illuminate\Support\Facades\Storage;


class ServiceController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan gambar (opsional)
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('service_images', 'public');
        }

        // Buat service baru
        $service = Services::create($validated);

        return response()->json([
            'message' => 'Service created successfully',
            'service' => $service,
            'success'=> true
        ], 201);
    }
    // Menampilkan semua data layanan
    public function index()
    {
        $services = Services::all();
        return response()->json(['success' => true, 'data' => $services]);
        
    }

    // Menampilkan data layanan berdasarkan ID
    public function show($id)
    {
        $service = Services::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $service]);
    }
    public function update(Request $request, $id)
    {
        $service = Services::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan gambar baru (opsional)
        if ($request->hasFile('image')) {
            if ($service->image) {
                // Hapus gambar lama
                Storage::delete('public/' . $service->image);
            }
            $validated['image'] = $request->file('image')->store('service_images', 'public');
        }

        // Update data layanan
        $service->update($validated);

        return response()->json([
            'message' => 'Service updated successfully',
            'service' => $service,
            'success'=> true
        ]);
    }
    public function destroy($id)
    {
        $service = Services::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        // Hapus gambar yang terkait jika ada
        if ($service->image) {
            Storage::delete('public/' . $service->image);
        }

        // Hapus data layanan
        $service->delete();

        return response()->json(['message' => 'Service deleted successfully']);
    }

}
