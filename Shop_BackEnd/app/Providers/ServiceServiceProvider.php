<?php

namespace App\Providers;

use App\Services\Cart\CartService;
use App\Services\Cart\CartServiceEloquent;
use App\Services\Category\CategoryService;
use App\Services\Category\CategoryServiceEloquent;
use App\Services\City\CityService;
use App\Services\City\CityServiceEloquent;
use App\Services\Color\ColorService;
use App\Services\Color\ColorServiceEloquent;
use App\Services\Comment\CommentService;
use App\Services\Comment\CommentServiceEloquent;
use App\Services\District\DistrictService;
use App\Services\District\DistrictServiceEloquent;
use App\Services\Image\ImageService;
use App\Services\Image\ImageServiceEloquent;
use App\Services\Material\MaterialService;
use App\Services\Material\MaterialServiceEloquent;
use App\Services\Order\OrderService;
use App\Services\Order\OrderServiceEloquent;
use App\Services\OrderDetail\OrderDetailService;
use App\Services\OrderDetail\OrderDetailServiceEloquent;
use App\Services\Product\ProductService;
use App\Services\Product\ProductServiceEloquent;
use App\Services\ProductImage\ProductImageService;
use App\Services\ProductImage\ProductImageServiceEloquent;
use App\Services\Profile\ProfileService;
use App\Services\Profile\ProfileServiceEloquent;
use App\Services\Size\SizeService;
use App\Services\Size\SizeServiceEloquent;
use App\Services\User\UserService;
use App\Services\User\UserServiceEloquent;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CategoryService::class, CategoryServiceEloquent::class);
        $this->app->singleton(ProductService::class, ProductServiceEloquent::class);
        $this->app->singleton(UserService::class, UserServiceEloquent::class);
        $this->app->singleton(CityService::class, CityServiceEloquent::class);
        $this->app->singleton(DistrictService::class, DistrictServiceEloquent::class);
        $this->app->singleton(CartService::class, CartServiceEloquent::class);
        $this->app->singleton(ProfileService::class, ProfileServiceEloquent::class);
        $this->app->singleton(OrderService::class, OrderServiceEloquent::class);
        $this->app->singleton(ImageService::class, ImageServiceEloquent::class);
        $this->app->singleton(ColorService::class, ColorServiceEloquent::class);
        $this->app->singleton(MaterialService::class, MaterialServiceEloquent::class);
        $this->app->singleton(SizeService::class, SizeServiceEloquent::class);
        $this->app->singleton(ProductImageService::class, ProductImageServiceEloquent::class);
        $this->app->singleton(OrderDetailService::class, OrderDetailServiceEloquent::class);
        $this->app->singleton(CommentService::class, CommentServiceEloquent::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
