<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\CartSummaryService::class, fn () => new \App\Services\CartSummaryService());
        $this->app->singleton(\App\Services\ImageOptimizationService::class, fn () => new \App\Services\ImageOptimizationService());
    }

    public function boot(): void
    {
        $shareCategories = function ($view) {
            $view->with('categories', Cache::remember('categories_active', 3600, fn () => \App\Models\Category::where('is_active', true)->orderBy('name')->get()));
        };
        View::composer(['components.search-modal', 'components.navbar'], $shareCategories);
    }
}
