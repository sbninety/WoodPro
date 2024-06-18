<?php

namespace App\Providers;

use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\CartRepositoryEloquent;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryEloquent;
use App\Repositories\City\CityRepository;
use App\Repositories\City\CityRepositoryEloquent;
use App\Repositories\Color\ColorRepository;
use App\Repositories\Color\ColorRepositoryEloquent;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryEloquent;
use App\Repositories\District\DistrictRepository;
use App\Repositories\District\DistrictRepositoryEloquent;
use App\Repositories\Image\ImageRepository;
use App\Repositories\Image\ImageRepositoryEloquent;
use App\Repositories\Material\MaterialRepository;
use App\Repositories\Material\MaterialRepositoryEloquent;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderRepositoryEloquent;
use App\Repositories\OrderDetail\OrderDetailRepository;
use App\Repositories\OrderDetail\OrderDetailRepositoryEloquent;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductRepositoryEloquent;
use App\Repositories\ProductImage\ProductImageRepository;
use App\Repositories\ProductImage\ProductImageRepositoryEloquent;
use App\Repositories\Profile\ProfileRepository;
use App\Repositories\Profile\ProfileRepositoryEloquent;
use App\Repositories\Size\SizeRepository;
use App\Repositories\Size\SizeRepositoryEloquent;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CategoryRepository::class, CategoryRepositoryEloquent::class);
        $this->app->singleton(ProductRepository::class, ProductRepositoryEloquent::class);
        $this->app->singleton(UserRepository::class, UserRepositoryEloquent::class);
        $this->app->singleton(CityRepository::class, CityRepositoryEloquent::class);
        $this->app->singleton(DistrictRepository::class, DistrictRepositoryEloquent::class);
        $this->app->singleton(CartRepository::class, CartRepositoryEloquent::class);
        $this->app->singleton(ProfileRepository::class, ProfileRepositoryEloquent::class);
        $this->app->singleton(OrderRepository::class, OrderRepositoryEloquent::class);
        $this->app->singleton(ImageRepository::class, ImageRepositoryEloquent::class);
        $this->app->singleton(ColorRepository::class, ColorRepositoryEloquent::class);
        $this->app->singleton(MaterialRepository::class, MaterialRepositoryEloquent::class);
        $this->app->singleton(SizeRepository::class, SizeRepositoryEloquent::class);
        $this->app->singleton(ProductImageRepository::class, ProductImageRepositoryEloquent::class);
        $this->app->singleton(OrderDetailRepository::class, OrderDetailRepositoryEloquent::class);
        $this->app->singleton(CommentRepository::class, CommentRepositoryEloquent::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
