<?php

namespace App\Services;

class StripePayment implements PaymentGatewayInterface
{
    public function pay($amount)
    {
        return "Paid ₹$amount via Stripe";
    }
}
