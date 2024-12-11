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
        // Ambil inputan dari request tanpa validasi otomatis
        $name = $request->input('name');
        $description = $request->input('description');
        $price = $request->input('price');
        $stock = $request->input('stock');
        $imagePath = null;


        // Menyimpan gambar jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Buat data product baru tanpa validator
        $product = Products::create([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
            'image' => $imagePath
        ]);

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
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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
        $data = [
            'product_id' => $product->product_id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'image' => asset('storage/' . $product->image),
        ];

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
}
