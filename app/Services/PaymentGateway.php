<?php

namespace App\Services;


interface PaymentGateway
{
    public function processPayment($amount);
}
