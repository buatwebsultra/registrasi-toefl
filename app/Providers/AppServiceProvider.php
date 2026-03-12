<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(\RyanChandler\LaravelCloudflareTurnstile\Contracts\ClientInterface::class, function ($app) {
            return new \App\Services\TurnstileClient($app['config']->get('services.turnstile.secret'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap for pagination
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        // Register QR Code facade alias
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('QrCode', \SimpleSoftwareIO\QrCode\Facades\QrCode::class);

        // FALLBACK: Register 'turnstile' validation rule manually
        \Illuminate\Support\Facades\Validator::extend('turnstile', function ($attribute, $value, $parameters, $validator) {
            $rule = new \RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;
            
            $passes = true;
            $rule->validate($attribute, $value, function($message) use (&$passes) {
                $passes = false;
            });
            
            return $passes;
        }, 'Verifikasi profil keamanan (Turnstile) tidak berhasil. Silakan muat ulang halaman dan coba lagi.');
    }
}
