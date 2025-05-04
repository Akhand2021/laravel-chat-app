<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use ArdaGnsrn\Ollama\Ollama;

class OllamaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Ollama::class, function ($app) {
            return new Ollama; // You can pass config here if needed
        });
    }

    public function boot()
    {
        //
    }
}
