<?php

namespace App\Providers;

use App\Contracts\CartServiceInterface;
use App\Contracts\CartStorageInterface;
use App\Contracts\CheckoutServiceInterface;
use App\Services\Cart\CartStorageManager;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\View\Composers\CartComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CartStorageInterface::class, CartStorageManager::class);
        $this->app->bind(CartServiceInterface::class, CartService::class);
        $this->app->bind(CheckoutServiceInterface::class, CheckoutService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', CartComposer::class);
    }
}