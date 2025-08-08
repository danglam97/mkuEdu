<?php

namespace App\Providers;

use App\Models\Banner;
use App\Observers\BannerObserver;
use Illuminate\Support\ServiceProvider;

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
        Banner::observe(BannerObserver::class);
    }
}
