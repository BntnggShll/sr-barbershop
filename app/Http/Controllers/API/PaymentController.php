<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;
use Stripe\Stripe;
use Stripe\Charge;
use Carbon\Carbon;

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
        $payments = Payments::with(['payable', 'user'])->get();
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


    public function stripePost(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    
        // Periksa apakah data yang dikirim adalah array atau objek tunggal
        $isArray = is_array($request->input('payments'));
    
        // Tentukan validasi berdasarkan tipe data
        $rules = $isArray
            ? [
                'payments' => 'required|array',
                'payments.*.payable_type' => 'required',
                'payments.*.payable_id' => 'required',
                'payments.*.user_id' => 'required|exists:users,user_id',
                'payments.*.amount' => 'required|numeric|min:0',
                'payments.*.payment_method' => 'required|in:Credit Card,E-Wallet',
            ]
            : [
                'payable_type' => 'required',
                'payable_id' => 'required',
                'user_id' => 'required|exists:users,user_id',
                'amount' => 'required|numeric|min:0',
                'payment_method' => 'required|in:Credit Card,E-Wallet',
            ];
    
        $validated = $request->validate($rules);
    
        $responses = [];
    
        if ($isArray) {
            // Proses setiap item dalam array
            foreach ($validated['payments'] as $paymentData) {
                $responses[] = $this->processPayment($paymentData, $request->stripeToken);
            }
        } else {
            // Proses objek tunggal
            $responses[] = $this->processPayment($validated, $request->stripeToken);
        }
    
        return response()->json($responses);
    }
    
    private function processPayment($paymentData, $stripeToken)
    {
        try {
            $paymentData['payment_status'] = 'Completed';
            $paymentData['transaction_date'] = Carbon::now();
    
            // Simpan data pembayaran
            Payments::create($paymentData);
    
            // Membuat charge ke Stripe
            Charge::create([
                'amount' => $paymentData['amount'],
                'currency' => 'usd',
                'source' => $stripeToken,
                'description' => 'Payment for Order',
            ]);
    
            return ['success' => true, 'payable_id' => $paymentData['payable_id']];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage(), 'payable_id' => $paymentData['payable_id']];
        }
    }
    

  

}