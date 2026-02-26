<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\CartSummaryService::class, fn() => new \App\Services\CartSummaryService());
        $this->app->singleton(\App\Services\ImageOptimizationService::class, fn() => new \App\Services\ImageOptimizationService());
    }

    public function boot(): void
    {
        // 1. FORZAR HTTPS EN RENDER (Nuestro nuevo código)
        if (env('APP_ENV') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // 2. TU CÓDIGO ORIGINAL (¡Déjalo tal cual lo tenías!)
        $shareCategories = function ($view) {
            // (Aquí va el código que ya tenías sobre Cache::remember...)
            // ... yo lo abrevio aquí, pero tú deja tu línea completa original.
        };
        \Illuminate\Support\Facades\View::composer(['components.search-modal', 'components.navbar'], $shareCategories);
    }

}
