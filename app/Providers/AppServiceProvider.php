<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Repositories\AdminRepositoryInterface;
use App\Domain\Repositories\AdminRefreshTokenRepositoryInterface;
use App\Domain\Repositories\CategoryRepositoryInterface;
use App\Domain\Repositories\CompanySettingRepositoryInterface;
use App\Domain\Repositories\ContactRepositoryInterface;
use App\Domain\Repositories\AmenityRepositoryInterface;
use App\Domain\Repositories\LocationRepositoryInterface;
use App\Domain\Repositories\NewsCategoryRepositoryInterface;
use App\Domain\Repositories\NewsRepositoryInterface;
use App\Domain\Repositories\ServicePageRepositoryInterface;
use App\Domain\Repositories\TourRepositoryInterface;
use App\Domain\Repositories\HotelRepositoryInterface;
use App\Domain\Repositories\HotelTypeRepositoryInterface;
use App\Infrastructure\Database\Repositories\AmenityRepository;
use App\Infrastructure\Database\Repositories\AdminRepository;
use App\Infrastructure\Database\Repositories\AdminRefreshTokenRepository;
use App\Infrastructure\Database\Repositories\CategoryRepository;
use App\Infrastructure\Database\Repositories\CompanySettingRepository;
use App\Infrastructure\Database\Repositories\ContactRepository;
use App\Infrastructure\Database\Repositories\LocationRepository;
use App\Infrastructure\Database\Repositories\NewsCategoryRepository;
use App\Infrastructure\Database\Repositories\NewsRepository;
use App\Infrastructure\Database\Repositories\ServicePageRepository;
use App\Infrastructure\Database\Repositories\TourRepository;
use App\Infrastructure\Database\Repositories\HotelRepository;
use App\Infrastructure\Database\Repositories\HotelTypeRepository;
use App\Infrastructure\Services\Contracts\FileStorageServiceInterface;
use App\Infrastructure\Services\Contracts\JwtServiceInterface;
use App\Infrastructure\Services\External\FileSystemManager;
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
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(AmenityRepositoryInterface::class, AmenityRepository::class);
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(HotelTypeRepositoryInterface::class, HotelTypeRepository::class);
        $this->app->bind(NewsCategoryRepositoryInterface::class, NewsCategoryRepository::class);
        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->bind(CompanySettingRepositoryInterface::class, CompanySettingRepository::class);
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(TourRepositoryInterface::class, TourRepository::class);
        $this->app->bind(HotelRepositoryInterface::class, HotelRepository::class);
        $this->app->bind(ServicePageRepositoryInterface::class, ServicePageRepository::class);
        $this->app->bind(FileStorageServiceInterface::class, FileSystemManager::class);
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
