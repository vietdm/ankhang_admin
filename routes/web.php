<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WithdrawController;
use Illuminate\Support\Facades\Route;

Route::get('auth0/login', [AuthController::class, 'login']);
Route::post('auth0/logout', [AuthController::class, 'logout']);
Route::post('auth0/login', [AuthController::class, 'loginPost']);

Route::middleware('admin.auth')->group(function () {
    Route::get('/', [HomeController::class, 'home']);
    Route::get('/w', [HomeController::class, 'withdraw']); // w => withdraw
    Route::get('/c', [HomeController::class, 'createOrder']); // c => createOrder
    Route::get('/order/accepts', [OrderController::class, 'accepts']);
    Route::post('/order/{id}/accept', [OrderController::class, 'accept']);
    Route::post('/order/{id}/payed', [OrderController::class, 'payed']);
    Route::post('/order/{id}/cancel', [OrderController::class, 'cancel']);
    Route::get('/order/export', [OrderController::class, 'export']);
    Route::post('/order/create', [OrderController::class, 'create']);
    Route::post('/withdraw/{id}/accept', [WithdrawController::class, 'accept']);
    Route::post('/withdraw/{id}/cancel', [WithdrawController::class, 'cancel']);
});
