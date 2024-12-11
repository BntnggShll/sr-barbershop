<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    // Menampilkan semua data income
    public function index()
    {
        $incomes = Income::with('worker')->get();

        return response()->json([
            'success' => true,
            'data' => $incomes,
        ]);
    }

    // Menambahkan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'worker_id' => 'required|exists:users,user_id',
            'income' => 'required|numeric',
            'description' => 'required|string',
            'report_date' => 'required|date',
        ]);

        $income = Income::create($validated);

        return response()->json([
            'success' => true,
            'data' => $income,
        ], 201);
    }

    // Menampilkan data berdasarkan ID
    public function show($id)
    {
        $income = Income::with('worker')->find($id);

        if (!$income) {
            return response()->json(['message' => 'Income not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $income,
        ]);
    }

    // Memperbarui data berdasarkan ID
    public function update(Request $request, $id)
    {
        $income = Income::find($id);

        if (!$income) {
            return response()->json(['message' => 'Income not found'], 404);
        }

        $validated = $request->validate([
            'worker_id' => 'sometimes|exists:users,user_id',
            'income' => 'sometimes|numeric',
            'description' => 'sometimes|string',
            'report_date' => 'sometimes|date',
        ]);

        $income->update($validated);

        return response()->json([
            'success' => true,
            'data' => $income,
        ]);
    }

    // Menghapus data berdasarkan ID
    public function destroy($id)
    {
        $income = Income::find($id);

        if (!$income) {
            return response()->json(['message' => 'Income not found'], 404);
        }

        $income->delete();

        return response()->json([
            'success' => true,
            'message' => 'Income deleted successfully',
        ]);
    }
}
