<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\User;
use App\Models\Hotel;
use App\Models\NewsletterSubscriber;
use App\Observers\ReservationObserver;
use App\Observers\ReviewObserver;
use App\Observers\UserObserver;
use App\Observers\HotelObserver;
use App\Observers\NewsletterSubscriberObserver;

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
        // Registar Observers para notificações
        Reservation::observe(ReservationObserver::class);
        Review::observe(ReviewObserver::class);
        User::observe(UserObserver::class);
        Hotel::observe(HotelObserver::class);
        NewsletterSubscriber::observe(NewsletterSubscriberObserver::class);

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
