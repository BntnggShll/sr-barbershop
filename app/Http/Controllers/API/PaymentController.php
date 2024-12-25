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
    // public function createDanaPayment(Request $request)
    // {
    //     \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    //     \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

    //     $params = [
    //         'payment_type' => 'gopay', // DANA juga menggunakan struktur e-wallet seperti GoPay
    //         'transaction_details' => [
    //             'order_id' => 'order-' . uniqid(),
    //             'gross_amount' => $request->amount,
    //         ],
    //         'customer_details' => [
    //             'email' => $request->email,
    //             'phone' => $request->phone,
    //         ],
    //     ];

    //     try {
    //         $payment = \Midtrans\CoreApi::charge($params);

    //         return response()->json($payment);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function stripePost(Request $request)
    {
        // Set Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $validated = $request->validate([
            'payable_type' => 'required',
            'payable_id' => 'required',
            'user_id' => 'required|exists:users,user_id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Credit Card,E-Wallet',
        ]);

        $validated['payment_status'] = 'Completed';
        $validated['transaction_date'] = Carbon::now();
        $payment = Payments::create($validated);

        try {
            // Membuat charge dengan token yang diterima
            $charge = Charge::create([
                'amount' => $request->amount, // $100
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Payment for Order',
            ]);

            // Jika pembayaran berhasil
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Tangani jika ada error
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function googlepay(Request $request)
{
    // Set Stripe API key
    Stripe::setApiKey(env('STRIPE_SECRET'));

    // Validasi input
    $validated = $request->validate([
        'payable_type' => 'required',
        'payable_id' => 'required',
        'user_id' => 'required|exists:users,user_id',
        'amount' => 'required|numeric|min:0',
        'payment_method' => 'required|in:Credit Card,E-Wallet,Google Pay',
        'stripeToken' => 'required|string',
    ]);

    // Tambahkan status pembayaran dan tanggal transaksi
    $validated['payment_status'] = 'Completed';
    $validated['transaction_date'] = Carbon::now();

    try {
        // Membuat charge dengan Stripe
        $charge = Charge::create([
            'amount' => $validated['amount'] * 100, // Stripe menerima jumlah dalam cent (contoh: $10 -> 1000)
            'currency' => 'usd',
            'source' => $validated['stripeToken'],
            'description' => 'Payment for Order',
        ]);

        // Simpan ke dalam database
        $payment = Payments::create($validated);

        // Jika pembayaran berhasil
        return response()->json(['success' => true, 'message' => 'Payment successful!']);
    } catch (\Exception $e) {
        // Tangani jika ada error
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}


}