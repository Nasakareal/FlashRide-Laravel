<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ride_id'        => 'required|exists:rides,id',
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string'
        ]);

        $payment = Payment::create([
            'ride_id'        => $request->ride_id,
            'amount'         => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'status'         => 'completed'
        ]);

        return response()->json([
            'message' => 'Payment registered',
            'data'    => $payment
        ], 201);
    }
}
