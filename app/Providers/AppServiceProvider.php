<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Menus;
use App\Observers\BannerObserver;
use App\Observers\MenusObserver;
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
        Menus::observe(MenusObserver::class);
    }
}
