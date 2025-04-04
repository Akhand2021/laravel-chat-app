<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function makePayment(Request $request)
    {
        $amount = $request->input('amount');

        try {
            $response = $this->paymentService->processPayment($amount);
            return response()->json(['message' => $response], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment failed: ' . $e->getMessage()], 500);
        }
    }
}
