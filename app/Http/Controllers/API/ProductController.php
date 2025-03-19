<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function store(Request $request)
{
    // Ambil inputan dari request dengan validasi
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|integer|min:0', // Pastikan price adalah numeric
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk gambar
    ]);

    // Menyimpan gambar jika ada
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images', 'public');
    }

    // Tambahkan path gambar ke data yang telah divalidasi
    $validated['image'] = $imagePath;

    // Buat data produk baru
    $product = Products::create($validated);

    return response()->json([
        'message' => 'Product created successfully',
        'product' => $product,
        'success' => true
    ], 201);
}



    // Menampilkan semua data products
    public function index()
    {
        $product = Products::all();
        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }

    // Menampilkan data product berdasarkan ID
    public function show($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found',], 404);
        }

        return response()->json($product);
    }

    public function update(Request $request, $product_id)
    {
        $product = Products::find($product_id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
                'success' => false
            ], 404);
        }
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0',
            'image' => 'image|mimes:jpeg,png,jpg,gif',
        ]);
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }
        $product->update(array_merge($validated, ['image' => $imagePath ?? $product->image]));
        
        $data = Products::all();
        return response()->json([
            'success' => true,
            'product' => $data,
        ]); 
    }



    public function destroy($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Hapus gambar jika ada
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }

        // Hapus data product
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function stock(Request $request, $id)
{
    // Cari produk berdasarkan ID
    $product = Products::find($id);

    // Periksa apakah produk ditemukan
    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found',
        ], 404);
    }

    // Validasi input request
    $validated = $request->validate([
        'stock' => 'required|integer|min:1',
    ]);

    // Periksa apakah stok cukup untuk dikurangi
    if ($product->stock < $validated['stock']) {
        return response()->json([
            'success' => false,
            'message' => 'Insufficient stock',
        ], 400);
    }

    // Kurangi stok produk
    $product->decrement('stock', $validated['stock']);

    // Refresh data produk
    $product->refresh();

    // Berikan respons dengan data terbaru
    return response()->json([
        'success' => true,
        'message' => 'Stock updated successfully',
        'product' => $product,
    ]);
}

}
