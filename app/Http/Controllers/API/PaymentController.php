<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,reservation_id',
            'user_id' => 'required|exists:users,user_id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Credit Card,E-Wallet',
            'payment_status' => 'required|in:Pending,Completed,Failed',
            'transaction_date' => 'required|date',
        ]);

        // Buat data payment baru
        $payment = Payments::create($validated);

        return response()->json([
            'message' => 'Payment record created successfully',
            'payment' => $payment
        ], 201);
    }
    // Menampilkan semua data payments
    public function index()
    {
        $payments = Payments::all();
        return response()->json($payments);
    }

    // Menampilkan data payment berdasarkan ID
    public function show($id)
    {
        $payment = Payments::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment record not found'], 404);
        }

        return response()->json($payment);
    }
    public function update(Request $request, $id)
    {
        $payment = Payments::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment record not found'], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,reservation_id',
            'user_id' => 'required|exists:users,user_id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Credit Card,E-Wallet',
            'payment_status' => 'required|in:Pending,Completed,Failed',
            'transaction_date' => 'required|date',
        ]);

        // Update data payment
        $payment->update($validated);

        return response()->json([
            'message' => 'Payment record updated successfully',
            'payment' => $payment
        ]);
    }
    public function destroy($id)
    {
        $payment = Payments::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment record not found'], 404);
        }

        // Hapus data payment
        $payment->delete();

        return response()->json(['message' => 'Payment record deleted successfully']);
    }
    public function createDanaPayment(Request $request)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        $params = [
            'payment_type' => 'gopay', // DANA juga menggunakan struktur e-wallet seperti GoPay
            'transaction_details' => [
                'order_id' => 'order-' . uniqid(),
                'gross_amount' => $request->amount,
            ],
            'customer_details' => [
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];

        try {
            $payment = \Midtrans\CoreApi::charge($params);

            return response()->json($payment);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
