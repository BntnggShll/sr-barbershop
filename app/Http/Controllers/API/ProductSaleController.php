<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product_sales;

class ProductSaleController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'user_id' => 'required|exists:users,user_id',
            'admin_id' => 'required|exists:users,user_id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'sale_date' => 'required|date',
        ]);

        // Buat data penjualan produk baru
        $product_sale = Product_sales::create($validated);

        return response()->json([
            'message' => 'Product sale created successfully',
            'product_sale' => $product_sale
        ], 201);
    }
    // Menampilkan semua data penjualan produk
    public function index()
    {
        $product_sales = Product_sales::all();
        return response()->json($product_sales);
    }

    // Menampilkan data penjualan produk berdasarkan ID
    public function show($id)
    {
        $product_sale = Product_sales::find($id);

        if (!$product_sale) {
            return response()->json(['message' => 'Product sale not found'], 404);
        }

        return response()->json($product_sale);
    }
    public function update(Request $request, $id)
    {
        $product_sale = Product_sales::find($id);

        if (!$product_sale) {
            return response()->json(['message' => 'Product sale not found'], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'user_id' => 'required|exists:users,user_id',
            'admin_id' => 'required|exists:users,user_id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'sale_date' => 'required|date',
        ]);

        // Update data penjualan produk
        $product_sale->update($validated);

        return response()->json([
            'message' => 'Product sale updated successfully',
            'product_sale' => $product_sale
        ]);
    }
    public function destroy($id)
    {
        $product_sale = Product_sales::find($id);

        if (!$product_sale) {
            return response()->json(['message' => 'Product sale not found'], 404);
        }

        // Hapus data penjualan produk
        $product_sale->delete();

        return response()->json(['message' => 'Product sale deleted successfully']);
    }

}
