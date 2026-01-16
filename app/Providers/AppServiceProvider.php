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
        //
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
    }
}
