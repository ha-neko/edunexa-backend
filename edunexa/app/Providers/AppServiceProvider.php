<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Manually register the View service provider if it's not bound
        // This solves the "Target class [view] does not exist" error in API setups
        if (!$this->app->bound('view')) {
            $this->app->register(\Illuminate\View\ViewServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}