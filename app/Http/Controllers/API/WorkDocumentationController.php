<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\Work_documentation;
use Illuminate\Http\Request;
use Illuminate\Queue\Worker;
use Illuminate\Support\Facades\Storage;

class WorkDocumentationController extends Controller
{
    // Menampilkan semua dokumentasi kerja
    public function index()
    {
        $documentations = Work_documentation::all();
        $data = $documentations->map(function ($documentations) {
            return [
                'documentation_id' => $documentations->documentation_id,
                'worker_id' => $documentations->worker_id,
                'reservation_id' => $documentations->reservation_id,
                'description' => $documentations->description,
                'photo_url' => asset('storage/' . $documentations->photo_url),
            ];
        });
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {

        $path = $request->file('photo_url')->store('documentation', 'public');

        $documentation = Work_documentation::create([
            'worker_id' => $request->worker_id,
            'reservation_id' => $request->reservation_id,
            'photo_url' => $path,
            'description' => $request->description,
        ]);

        $data = [
            'documentation_id' => $documentation->documentation_id,
            'worker_id' => $documentation->worker_id,
            'reservation_id' => $documentation->reservation_id,
            'description' => $documentation->description,
            'photo_url' => asset('storage/' . $documentation->photo_url),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 201);
    }

    public function show($id)
    {
        $documentation = Work_documentation::findOrFail($id);

        $data = [
            'documentation_id' => $documentation->documentation_id,
            'worker_id' => $documentation->worker_id,
            'reservation_id' => $documentation->reservation_id,
            'description' => $documentation->description,
            'photo_url' => asset('storage/' . $documentation->photo_url),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $documentation = Work_documentation::findOrFail($id);

        // $request->validate([
        //     'worker_id' => 'sometimes|exists:users,user_id',
        //     'reservation_id' => 'sometimes|exists:reservations,reservation_id',
        //     'photo_url' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'description' => 'nullable|string',
        // ]);

        if ($request->hasFile('photo_url')) {
            if ($documentation->photo_url) {
                Storage::delete($documentation->photo_url);
            }
            $path = $request->file('photo_url')->store('documentation', 'public');
            $documentation->photo_url = $path;
        }

        $documentation->worker_id = $request->worker_id ?? $documentation->worker_id;
        $documentation->reservation_id = $request->reservation_id ?? $documentation->reservation_id;
        $documentation->description = $request->description ?? $documentation->description;

        $documentation->save();

        $data = [
            'documentation_id' => $documentation->documentation_id,
            'worker_id' => $documentation->worker_id,
            'reservation_id' => $documentation->reservation_id,
            'description' => $documentation->description,
            'photo_url' => asset('storage/' . $documentation->photo_url),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    // Menghapus dokumentasi kerja
    public function destroy($id)
    {
        $documentation = Work_documentation::findOrFail($id);

        // Hapus gambar dari storage
        if ($documentation->photo_url) {
            Storage::delete($documentation->photo_url);
        }

        // Hapus dokumentasi
        $documentation->delete();

        return response()->json(null, 204);
    }
}
