<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Public\Auth\AuthController;
use App\Http\Controllers\Api\Private\User\UserController;
use App\Http\Controllers\Api\Private\Select\SelectController;
use App\Http\Controllers\Api\Private\User\UserProfileController;
use App\Http\Controllers\Api\Private\Category\CategoryController;
use App\Http\Controllers\Api\Private\User\ChangePasswordController;
use App\Http\Controllers\Api\Private\Category\SubCategoryController;
use App\Http\Controllers\Api\V1\Dashboard\Product\ProductController;
use App\Http\Controllers\Api\V1\Dashboard\ProductMedia\ProductMediaController;

Route::prefix('v1/admin')->group(function () {

    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('/login','login');
        Route::post('/logout','logout');
    });
    Route::apiResources([
        "users"=> UserController::class,
        "categories" => CategoryController::class,
        "sub-categories" =>SubCategoryController::class,
        "ProductMedia" => ProductMediaController::class,
        "products" => ProductController::class,
    ]);
    Route::apiSingleton('profile', UserProfileController::class);
    Route::put('profile/change-password', ChangePasswordController::class);
    Route::prefix('selects')->group(function(){
        Route::get('', [SelectController::class, 'getSelects']);
    });

});
