<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::get('permissions', [UserController::class, 'get_system_permissions']);

Route::group(['middleware' => ['checkAuthentication', 'checkAutherization'], 'group' => 'products'], function () {
    Route::get('products', [ProductController::class, 'lists']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::post('products', [ProductController::class, 'store']);
    Route::put('update/{product}', [ProductController::class, 'update']);
    Route::delete('delete/{product}', [ProductController::class, 'delete']);
});

Route::group(['middleware' => ['checkAuthentication', 'checkAutherization'], 'group' => 'users'], function () {
    Route::get('logout', [UserController::class, 'logout']);
    Route::get('profile', [UserController::class, 'profile']);
});
