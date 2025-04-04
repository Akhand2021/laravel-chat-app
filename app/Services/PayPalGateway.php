<?php

namespace App\Services;

use App\Services\PaymentGateway;

class PayPalGateway implements PaymentGateway
{
    public function processpayment($amount)
    {
        return "Processing payment via PayPal: $$amount";
    }
}
