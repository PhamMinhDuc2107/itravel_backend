<?php

declare(strict_types=1);

use App\Interfaces\Http\Admin\Controllers\AdminAuthController;
use App\Interfaces\Http\Admin\Controllers\AdminAmenityController;
use App\Interfaces\Http\Admin\Controllers\AdminCategoryController;
use App\Interfaces\Http\Admin\Controllers\AdminCompanySettingController;
use App\Interfaces\Http\Admin\Controllers\AdminContactController;
use App\Interfaces\Http\Admin\Controllers\AdminHotelController;
use App\Interfaces\Http\Admin\Controllers\AdminHotelTypeController;
use App\Interfaces\Http\Admin\Controllers\AdminLocationController;
use App\Interfaces\Http\Admin\Controllers\AdminNewsCategoryController;
use App\Interfaces\Http\Admin\Controllers\AdminNewsController;
use App\Interfaces\Http\Admin\Controllers\AdminServicePageController;
use App\Interfaces\Http\Admin\Controllers\AdminTourController;
use App\Interfaces\Http\Admin\Middleware\AdminJwtAuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/auth')->group(function (): void {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('refresh', [AdminAuthController::class, 'refresh']);

    Route::middleware(AdminJwtAuthMiddleware::class)->group(function (): void {
        Route::get('me', [AdminAuthController::class, 'me']);
        Route::post('logout', [AdminAuthController::class, 'logout']);
    });
});

Route::prefix('admin')->middleware(AdminJwtAuthMiddleware::class)->group(function (): void {
    Route::get('amenities', [AdminAmenityController::class, 'index']);
    Route::get('amenities/{id}', [AdminAmenityController::class, 'show']);
    Route::post('amenities', [AdminAmenityController::class, 'store']);
    Route::put('amenities/{id}', [AdminAmenityController::class, 'update']);
    Route::delete('amenities/{id}', [AdminAmenityController::class, 'destroy']);

    Route::get('categories', [AdminCategoryController::class, 'index']);
    Route::get('categories/{id}', [AdminCategoryController::class, 'show']);
    Route::post('categories', [AdminCategoryController::class, 'store']);
    Route::put('categories/{id}', [AdminCategoryController::class, 'update']);
    Route::delete('categories/{id}', [AdminCategoryController::class, 'destroy']);

    Route::get('company-settings', [AdminCompanySettingController::class, 'index']);
    Route::get('company-settings/{id}', [AdminCompanySettingController::class, 'show']);
    Route::post('company-settings', [AdminCompanySettingController::class, 'store']);
    Route::put('company-settings/{id}', [AdminCompanySettingController::class, 'update']);
    Route::delete('company-settings/{id}', [AdminCompanySettingController::class, 'destroy']);

    Route::get('contacts', [AdminContactController::class, 'index']);
    Route::get('contacts/{id}', [AdminContactController::class, 'show']);
    Route::post('contacts', [AdminContactController::class, 'store']);
    Route::put('contacts/{id}', [AdminContactController::class, 'update']);
    Route::delete('contacts/{id}', [AdminContactController::class, 'destroy']);

    Route::get('locations', [AdminLocationController::class, 'index']);
    Route::get('locations/{id}', [AdminLocationController::class, 'show']);
    Route::post('locations', [AdminLocationController::class, 'store']);
    Route::put('locations/{id}', [AdminLocationController::class, 'update']);
    Route::delete('locations/{id}', [AdminLocationController::class, 'destroy']);

    Route::get('news-categories', [AdminNewsCategoryController::class, 'index']);
    Route::get('news-categories/{id}', [AdminNewsCategoryController::class, 'show']);
    Route::post('news-categories', [AdminNewsCategoryController::class, 'store']);
    Route::put('news-categories/{id}', [AdminNewsCategoryController::class, 'update']);
    Route::delete('news-categories/{id}', [AdminNewsCategoryController::class, 'destroy']);

    Route::get('news', [AdminNewsController::class, 'index']);
    Route::get('news/{id}', [AdminNewsController::class, 'show']);
    Route::post('news', [AdminNewsController::class, 'store']);
    Route::put('news/{id}', [AdminNewsController::class, 'update']);
    Route::delete('news/{id}', [AdminNewsController::class, 'destroy']);

    Route::get('tours', [AdminTourController::class, 'index']);
    Route::get('tours/{id}', [AdminTourController::class, 'show']);
    Route::post('tours', [AdminTourController::class, 'store']);
    Route::put('tours/{id}', [AdminTourController::class, 'update']);
    Route::delete('tours/{id}', [AdminTourController::class, 'destroy']);

    Route::get('hotels', [AdminHotelController::class, 'index']);
    Route::get('hotels/{id}', [AdminHotelController::class, 'show']);
    Route::post('hotels', [AdminHotelController::class, 'store']);
    Route::put('hotels/{id}', [AdminHotelController::class, 'update']);
    Route::delete('hotels/{id}', [AdminHotelController::class, 'destroy']);

    Route::get('hotel-types', [AdminHotelTypeController::class, 'index']);
    Route::get('hotel-types/{id}', [AdminHotelTypeController::class, 'show']);
    Route::post('hotel-types', [AdminHotelTypeController::class, 'store']);
    Route::put('hotel-types/{id}', [AdminHotelTypeController::class, 'update']);
    Route::delete('hotel-types/{id}', [AdminHotelTypeController::class, 'destroy']);

    Route::get('service-pages', [AdminServicePageController::class, 'index']);
    Route::get('service-pages/{id}', [AdminServicePageController::class, 'show']);
    Route::post('service-pages', [AdminServicePageController::class, 'store']);
    Route::put('service-pages/{id}', [AdminServicePageController::class, 'update']);
    Route::delete('service-pages/{id}', [AdminServicePageController::class, 'destroy']);
});
