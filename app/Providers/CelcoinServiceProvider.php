<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class CelcoinServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('celcoin', function() {
            return new Client([
                'base_uri' => env('CELCOIN_API_URL'),
                'cert' => env('CELCOIN_MTLS_CERT'),
                'ssl_key' => env('CELCOIN_MTLS_KEY'),
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            ]);
        });
    }

}
