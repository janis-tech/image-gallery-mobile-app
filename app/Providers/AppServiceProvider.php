<?php

namespace App\Providers;

use App\Services\ImageGalleryHttp\ImageGalleryHttpService;
use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ImageGalleryHttpServiceInterface::class,
            ImageGalleryHttpService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading();
    }
}
