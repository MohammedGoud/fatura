<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
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
Route::get('permissions', [RoleController::class, 'list_system_permissions']);

Route::group(['middleware' => ['checkAuthentication', 'checkAuthorization'], 'group' => 'products'], function () {
    Route::get('products', [ProductController::class, 'lists']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::post('products', [ProductController::class, 'store']);
    Route::put('update/{product}', [ProductController::class, 'update']);
    Route::delete('delete/{product}', [ProductController::class, 'delete']);
});

Route::group(['middleware' => ['checkAuthentication', 'checkAuthorization'], 'group' => 'users'], function () {
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('profile', [UserController::class, 'profile']);
});

Route::group(['middleware' => ['checkAuthentication', 'checkAuthorization'], 'group' => 'roles'], function () {
    Route::get('roles', [RoleController::class, 'lists']);
    Route::post('roles', [RoleController::class, 'store']);
});
