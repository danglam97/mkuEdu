<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(\App\Services\Web\Menu\MenuServiceInterface::class, \App\Services\Web\Menu\MenuService::class);
        $this->app->bind(\App\Services\Web\Post\PostServiceInterface::class, \App\Services\Web\Post\PostService::class);
        $this->app->bind(\App\Services\Web\Album\AlbumServiceInterface::class, \App\Services\Web\Album\AlbumService::class);
     
        $this->app->bind(\App\Services\Web\Major\MajorServiceInterface::class, \App\Services\Web\Major\MajorService::class);
        //:end-bindings:
    }
}
