<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WithdrawController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin.notauth')->group(function () {
    Route::get('auth0/login', [AuthController::class, 'login']);
    Route::post('auth0/login', [AuthController::class, 'loginPost']);
});

Route::middleware('admin.auth')->group(function () {
    Route::get('/', [HomeController::class, 'home']);
    Route::get('auth0/logout', fn () => redirect()->to('/'));
    Route::post('auth0/logout', [AuthController::class, 'logout']);

    Route::middleware('admin.role:confirm_order')->group(function () {
        Route::get('/order/confirm', [HomeController::class, 'confirmOrder']);
        Route::post('/order/{id}/accept', [OrderController::class, 'accept']);
        Route::post('/order/{id}/payed', [OrderController::class, 'payed']);
        Route::post('/order/{id}/cancel', [OrderController::class, 'cancel']);
        Route::get('/order/export', [OrderController::class, 'export']);
    });

    Route::middleware('admin.role:confirm_withdraw')->group(function () {
        Route::get('/withdraw/confirm', [HomeController::class, 'confirmWithdraw']);
        Route::post('/withdraw/{id}/accept', [WithdrawController::class, 'accept']);
        Route::post('/withdraw/{id}/cancel', [WithdrawController::class, 'cancel']);
    });
});
