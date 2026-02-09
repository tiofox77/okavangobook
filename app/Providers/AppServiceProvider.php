<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

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
        // View Composer para disponibilizar settings globalmente
        View::composer('*', function ($view) {
            try {
                $appName = Setting::get('app_name', config('app.name'));
                $view->with('appName', $appName);
            } catch (\Exception $e) {
                // Fallback se houver problema com Settings
                $view->with('appName', config('app.name'));
            }
        });
    }
}
