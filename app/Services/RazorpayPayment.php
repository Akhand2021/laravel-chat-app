<?php

namespace App\Services;

class RazorpayPayment implements PaymentGatewayInterface
{
    public function pay($amount)
    {
        return "Paid ₹$amount via Razorpay";
    }
}
