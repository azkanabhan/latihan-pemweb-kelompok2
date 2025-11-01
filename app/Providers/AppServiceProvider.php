<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;

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
        // Force HTTPS for generated URLs when behind ngrok or when app URL is https
        $appUrl = config('app.url');
        if ((is_string($appUrl) && str_starts_with($appUrl, 'https://')) || str_contains($appUrl ?? '', 'ngrok')) {
            URL::forceScheme('https');
        }
    }
}
