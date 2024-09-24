<?php

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
    'controller' => App\Http\Controllers\AuthController::class
], function ($router) {
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register')->name('register');
    Route::post('logout', 'logout')->name('logout');
    Route::post('refresh', 'refresh')->name('refresh');
    Route::match(['post', 'get'], 'me', 'me')->name('me');
});

Route::group(['middleware' => 'auth:api'], function(){
    Route::apiResource('orders', \App\Http\Controllers\OrderController::class);
    Route::apiResource('products', \App\Http\Controllers\ProductController::class);
});