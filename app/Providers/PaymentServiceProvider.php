<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StripePayment;
use App\Services\RazorpayPayment;
use App\Services\PaymentGatewayInterface;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, function ($app) {
            return request()->input('payment_method') === 'razorpay'
                ? new RazorpayPayment()
                : new StripePayment();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
