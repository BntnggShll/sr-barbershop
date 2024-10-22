<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Financial_reports;

class FinancialController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'admin_id' => 'required|exists:users,user_id',
            'total_income' => 'required|numeric',
            'total_expense' => 'required|numeric',
            'net_profit' => 'required|numeric',
            'report_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Buat data laporan keuangan baru
        $report = Financial_reports::create($validated);

        return response()->json([
            'message' => 'Financial report created successfully',
            'report' => $report
        ], 201);
    }
    // Menampilkan semua laporan keuangan
    public function index()
    {
        $reports = Financial_reports::all();
        return response()->json($reports);
    }

    // Menampilkan laporan keuangan berdasarkan ID
    public function show($id)
    {
        $report = Financial_reports::find($id);

        if (!$report) {
            return response()->json(['message' => 'Financial report not found'], 404);
        }

        return response()->json($report);
    }
    public function update(Request $request, $id)
    {
        $report = Financial_reports::find($id);

        if (!$report) {
            return response()->json(['message' => 'Financial report not found'], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'admin_id' => 'required|exists:users,user_id',
            'total_income' => 'required|numeric',
            'total_expense' => 'required|numeric',
            'net_profit' => 'required|numeric',
            'report_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Update data laporan keuangan
        $report->update($validated);

        return response()->json([
            'message' => 'Financial report updated successfully',
            'report' => $report
        ]);
    }
    public function destroy($id)
    {
        $report = Financial_reports::find($id);

        if (!$report) {
            return response()->json(['message' => 'Financial report not found'], 404);
        }

        // Hapus laporan keuangan
        $report->delete();

        return response()->json(['message' => 'Financial report deleted successfully']);
    }

}
