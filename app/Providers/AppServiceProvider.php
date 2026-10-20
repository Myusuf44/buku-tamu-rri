<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

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
    public function boot(Request $request): void
    {
        // Deteksi otomatis: Paksa HTTPS hanya jika diakses lewat Ngrok atau Reverse Proxy
        if (
            $request->header('x-forwarded-proto') === 'https' || 
            str_contains($request->getHost(), 'ngrok-free.dev')
        ) {
            URL::forceScheme('https');
        }
    }
}