<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PaymentGateway;
use App\Services\StripeGateway;
use App\Services\PayPalGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(PaymentGateway::class, PayPalGateway::class);
        // $this->app->singleton(PaymentGateway::class, function () {
        //     return new StripeGateway();
        // });

        $this->app->bind(PaymentGateway::class, function ($app) {
            $gateway = request()->input('payment_method', 'stripe'); // Default Stripe
            return $gateway === 'paypal' ? new PayPalGateway() : new StripeGateway();
        });
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
