<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
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
        Route::get('tasks', [ProductController::class, 'getTasks']);
        Route::put('{product}', [ProductController::class, 'update']);
    });

    /**
     * Exports
     */
    Route::group(['prefix' => 'exports'], function () {
        Route::get('{export}/download', [ExportController::class, 'download']);
        Route::get('', [ExportController::class, 'index']);
        Route::post('{type}/create', [ExportController::class, 'export']);
    });

    /**
     * Imports
     */
    Route::group(['prefix' => 'imports'], function () {
        Route::get('', [ImportController::class, 'index']);
        Route::post('{type}/upload', [ImportController::class, 'upload']);
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

    /**
     * Notifications
     */
    Route::group(['prefix' => 'notifications'], function () {
        Route::get('', [NotificationController::class, 'index']);
        Route::post('read-all', [NotificationController::class, 'readAll']);
    });

    /**
     * Warehouse
     */
    Route::group(['prefix' => 'warehouses'], function () {
        Route::get('', [WarehouseController::class, 'index']);
        Route::get('leftovers', [WarehouseController::class, 'getLeftovers']);
        Route::get('groups', [WarehouseController::class, 'getGroups']);
    });
});
