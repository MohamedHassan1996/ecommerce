<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Select\SelectController;
use App\Http\Controllers\Api\V1\Dashboard\Auth\AuthController;
use App\Http\Controllers\Api\V1\Dashboard\User\UserController;
use App\Http\Controllers\Api\V1\Dashboard\Order\OrderController;
use App\Http\Controllers\Api\V1\Dashboard\Stats\StatsController;
use App\Http\Controllers\Api\V1\Dashboard\Client\ClientController;
use App\Http\Controllers\Api\V1\Dashboard\Product\ProductController;
use App\Http\Controllers\Api\V1\Dashboard\User\UserProfileController;
use App\Http\Controllers\Api\V1\Dashboard\Category\CategoryController;
use App\Http\Controllers\Api\V1\Dashboard\Client\ClientEmailController;
use App\Http\Controllers\Api\V1\Dashboard\Client\ClientPhoneController;
use App\Http\Controllers\Api\V1\Dashboard\Client\ClientAdressController;
use App\Http\Controllers\Api\V1\Dashboard\User\ChangePasswordController;
use App\Http\Controllers\Api\V1\Dashboard\Category\SubCategoryController;
use App\Http\Controllers\Api\V1\Dashboard\ProductMedia\ProductMediaController;

Route::prefix('v1/admin')->group(function () {

    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('/login','login');
        Route::post('/logout','logout');
    });
    Route::apiResources([
        "categories" => CategoryController::class,
        "sub-categories" =>SubCategoryController::class,
        "product-media" => ProductMediaController::class,
        "products" => ProductController::class,
        "clients" => ClientController::class,
        "client-phones" => ClientPhoneController::class,
        "client-emails"=> ClientEmailController::class,
        "client-addresses"=>ClientAdressController::class,
        "orders" => OrderController::class,
    ]);
    Route::apiResource('users', UserController::class);
    Route::apiSingleton('profile', UserProfileController::class);
    Route::put('profile/change-password', ChangePasswordController::class);
    Route::prefix('selects')->group(function(){
        Route::get('', [SelectController::class, 'getSelects']);
    });
    Route::get('/stats',StatsController::class);

});
