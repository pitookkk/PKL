<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use App\Observers\OrderObserver;
use App\Observers\ReviewObserver;
use App\Observers\ProductObserver;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191); // Good practice for older MySQL
        Review::observe(ReviewObserver::class);
        Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
    }
}
