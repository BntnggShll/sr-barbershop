<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    // Menampilkan semua data expense
    public function index()
    {
        $expenses = Expense::all();

        return response()->json([
            'success' => true,
            'data' => $expenses,
        ]);
    }

    // Menambahkan data baru
    public function store(Request $request)
    {

        $expense = Expense::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $expense,
        ], 201);
    }

    // Menampilkan data berdasarkan ID
    public function show($id)
    {
        $expense = Expense::with('admin')->find($id);

        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $expense,
        ]);
    }

    // Memperbarui data berdasarkan ID
    public function update(Request $request, $id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        $validated = $request->validate([
            'admin_id' => 'sometimes|exists:users,user_id',
            'expense' => 'sometimes|numeric',
            'description' => 'sometimes|string',
            'report_date' => 'sometimes|date',
        ]);

        $expense->update($validated);

        return response()->json([
            'success' => true,
            'data' => $expense,
        ]);
    }

    // Menghapus data berdasarkan ID
    public function destroy($id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Expense deleted successfully',
        ]);
    }
}
