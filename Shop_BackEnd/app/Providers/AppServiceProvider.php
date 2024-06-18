<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Observers\CommentObserver;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
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
        User::observe(UserObserver::class);
        Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
        Comment::observe(CommentObserver::class);
    }
}
