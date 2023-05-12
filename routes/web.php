<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('auth0/login', [AuthController::class, 'login']);
Route::post('auth0/logout', [AuthController::class, 'logout']);
Route::post('auth0/login', [AuthController::class, 'loginPost']);

Route::middleware('admin.auth')->group(function () {
    Route::get('/', [HomeController::class, 'home']);
    Route::get('/order/accepts', [OrderController::class, 'accepts']);
    Route::post('/order/{id}/accept', [OrderController::class, 'accept']);
    Route::post('/order/{id}/payed', [OrderController::class, 'payed']);
    Route::post('/order/{id}/cancel', [OrderController::class, 'cancel']);
});
