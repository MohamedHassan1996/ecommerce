<?php

use App\Http\Controllers\Api\Private\User\UserProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Private\Select\SelectController;
use App\Http\Controllers\Api\Private\Category\CategoryController;
use App\Http\Controllers\Api\Private\SubCategory\SubCategoryController;
use App\Http\Controllers\Api\Private\User\ChangePasswordController;
use App\Http\Controllers\Api\Private\User\UserController;
use App\Http\Controllers\Api\Public\Auth\AuthController;

Route::prefix('v1/')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::prefix('users')->group(function () {
        Route::get('', [UserController::class, 'index']);
        Route::post('create', [UserController::class, 'create']);
        Route::get('edit', [UserController::class, 'edit']);
        Route::put('update', [UserController::class, 'update']);
        Route::delete('destroy', [UserController::class, 'destroy']);
    });

    Route::apiSingleton('profile', UserProfileController::class);

    Route::put('profile/change-password', ChangePasswordController::class);

    Route::prefix('categories')->group(function () {
        Route::get('', [CategoryController::class, 'index']);
        Route::post('create', [CategoryController::class, 'create']);
        Route::get('edit', [CategoryController::class, 'edit']);
        Route::put('update', [CategoryController::class, 'update']);
        Route::delete('destroy', [CategoryController::class, 'destroy']);
    });

    Route::prefix('sub-categories')->group(function () {
        Route::get('', [SubCategoryController::class, 'index']);
        Route::post('create', [SubCategoryController::class, 'create']);
        Route::get('edit', [SubCategoryController::class, 'edit']);
        Route::put('update', [SubCategoryController::class, 'update']);
        Route::delete('destroy', [SubCategoryController::class, 'destroy']);
    });

    Route::prefix('selects')->group(function(){
        Route::get('', [SelectController::class, 'getSelects']);
    });

});
