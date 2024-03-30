<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Resources\Event\EventResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
/**
 * Auth protected routes
 */
Route::group(['middleware' => ['auth:api,packer']], function () {

    /**
     * Auth routes
     */
    Route::delete('auth/logout', [AuthController::class, 'logout']);

    /**
     * Users routes
     */
    Route::get('users/me', [UserController::class, 'me']);

    Route::group(['prefix' => 'products'], function () {
        Route::get('', [ProductController::class, 'index']);
        Route::put('{product}', [ProductController::class, 'update']);
    });

    /**
     * Categories
     */
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);

    /**
     * Packers
     */
    Route::resource('packers', PackerController::class)->except(['show', 'create', 'edit']);

    /**
     * Packages
     */
    Route::group(['prefix' => 'packages'], function () {
        Route::get('', [PackageController::class, 'getPackage']);
        Route::post('products/{product}', [PackageController::class, 'addProduct']);
        Route::delete('products/{product}', [PackageController::class, 'removeProduct']);

        Route::post('{package}/send', [PackageController::class, 'send']);
        Route::get('list', [PackageController::class, 'index']);
    });
});
