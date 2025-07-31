<?php

namespace App\Providers;

use App\Models\Product\Product;
use App\Observers\ProductObserver;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\Contracts\CategoryRepositoryInterface;
use App\Repositories\Notification\Contracts\NotificationRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Package\Contracts\PackageRepositoryInterface;
use App\Repositories\Package\PackageRepository;
use App\Repositories\Packer\Contracts\PackerRepositoryInterface;
use App\Repositories\Packer\PackerRepository;
use App\Repositories\Product\Contracts\ProductRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Warehouse\Contracts\WarehouseRepositoryInterface;
use App\Repositories\Warehouse\WarehouseRepository;
use App\Services\Bimpsoft\BimpsoftService;
use App\Services\Bimpsoft\Contracts\BimpsoftServiceInterface;
use App\Services\Category\CategoryService;
use App\Services\Category\Contracts\CategoryServiceInterface;
use App\Services\Notification\Contracts\NotificationServiceInterface;
use App\Services\Notification\NotificationService;
use App\Services\Package\Contracts\PackageServiceInterface;
use App\Services\Package\PackageService;
use App\Services\Packer\Contracts\PackerServiceInterface;
use App\Services\Packer\PackerService;
use App\Services\Product\Contracts\ProductServiceInterface;
use App\Services\Product\ProductService;
use App\Services\Warehouse\Contracts\WarehouseServiceInterface;
use App\Services\Warehouse\WarehouseService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /*
         * Services
         */
        $this->app->singleton(ProductServiceInterface::class, ProductService::class);
        $this->app->singleton(CategoryServiceInterface::class, CategoryService::class);
        $this->app->singleton(PackerServiceInterface::class, PackerService::class);
        $this->app->singleton(PackageServiceInterface::class, PackageService::class);
        $this->app->singleton(BimpsoftServiceInterface::class, BimpsoftService::class);
        $this->app->singleton(NotificationServiceInterface::class, NotificationService::class);
        $this->app->singleton(WarehouseServiceInterface::class, WarehouseService::class);

        /*
         * Repositories
         */
        $this->app->singleton(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->singleton(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->singleton(PackerRepositoryInterface::class, PackerRepository::class);
        $this->app->singleton(PackageRepositoryInterface::class, PackageRepository::class);
        $this->app->singleton(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->singleton(WarehouseRepositoryInterface::class, WarehouseRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
    }
}
