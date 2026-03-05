<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Repositories\AdminRepositoryInterface;
use App\Domain\Repositories\AdminRefreshTokenRepositoryInterface;
use App\Infrastructure\Database\Repositories\AdminRepository;
use App\Infrastructure\Database\Repositories\AdminRefreshTokenRepository;
use App\Infrastructure\Services\Contracts\JwtServiceInterface;
use App\Infrastructure\Services\External\JwtService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(AdminRefreshTokenRepositoryInterface::class, AdminRefreshTokenRepository::class);
        $this->app->bind(JwtServiceInterface::class, JwtService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
