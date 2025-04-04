<?php

namespace App\Services;

use App\Services\PaymentGateway;

class StripeGateway implements PaymentGateway
{
    public function processPayment($amount)
    {
        return "Processing payment of $amount through Stripe.";
    }
}
