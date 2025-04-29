<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\Interfaces\BaseServiceInterface;
use App\Services\Impl\BaseService;

use App\Services\Interfaces\Auth\AuthServiceInterface;
use App\Services\Impl\Auth\AuthService;
use App\Services\Impl\Permission\PermissionService;
use App\Services\Interfaces\User\UserCatalogueServiceInterface;
use App\Services\Impl\User\UserCatalogueService;
use App\Services\Interfaces\User\UserServiceInterface;
use App\Services\Impl\User\UserService;
use App\Services\Interfaces\Permission\PermissionServiceInterface;
use App\Services\Interfaces\Post\PostCatalogueServiceInterface;
use App\Services\Impl\Post\PostCatalogueService;
use App\Services\Interfaces\Post\PostServiceInterface;
use App\Services\Impl\Post\PostService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BaseServiceInterface::class, BaseService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(UserCatalogueServiceInterface::class, UserCatalogueService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
        $this->app->bind(PostCatalogueServiceInterface::class, PostCatalogueService::class);
        $this->app->bind(PostServiceInterface::class, PostService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
