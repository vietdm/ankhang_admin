<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AkgController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawController;
use Illuminate\Support\Facades\Route;

include __DIR__ . DIRECTORY_SEPARATOR . 'hack.php';

Route::middleware('admin.notauth')->group(function () {
    Route::get('auth0/login', [AuthController::class, 'login']);
    Route::post('auth0/login', [AuthController::class, 'loginPost']);
});

Route::middleware('admin.auth')->group(function () {
    Route::get('/', [DashboardController::class, 'home']);
    Route::post('/get-dashboard', [DashboardController::class, 'getDashboard']);
    Route::get('auth0/logout', fn () => redirect()->to('/'));
    Route::post('auth0/logout', [AuthController::class, 'logout']);

    Route::prefix('dashboard')->group(function () {
        Route::get('bonus', [DashboardController::class, 'bonus']);
        Route::get('export', [DashboardController::class, 'export']);
    });

    Route::prefix('akg')->group(function () {
        Route::get('all', [AkgController::class, 'all']);
        Route::get('transfer', [AkgController::class, 'transfer']);
        Route::post('transfer', [AkgController::class, 'transferPost']);
    });

    Route::middleware('admin.role:all')->group(function () {
        Route::get('/accounts', [AccountController::class, 'accounts']);
        Route::post('/account/create', [AccountController::class, 'createAccount']);
        Route::prefix('lucky-event')->group(function() {
            Route::get('/', [EventController::class, 'lucky']);
            Route::post('/update', [EventController::class, 'luckyUpdate']);
        });
    });

    Route::middleware('admin.role:view_user')->prefix('user')->group(function () {
        Route::get('/all', [UserController::class, 'all']);
        Route::post('/change_password', [UserController::class, 'changePassword']);
        Route::get('/{id}', [UserController::class, 'detail']);
        Route::post('/{id}/children', [UserController::class, 'children']);
    });

    Route::middleware('admin.role:confirm_order')->group(function () {
        Route::get('/order/confirm', [HomeController::class, 'confirmOrder']);
        Route::post('/order/{id}/accept', [OrderController::class, 'accept']);
        Route::post('/order/{id}/payed', [OrderController::class, 'payed']);
        Route::post('/order/{id}/cancel', [OrderController::class, 'cancel']);
        Route::get('/order/export', [OrderController::class, 'export']);
    });

    Route::middleware('admin.role:transfer_order')->group(function () {
        Route::get('/order/transfer', [HomeController::class, 'transferOrder']);
        Route::post('/order/confirmed', [OrderController::class, 'listOrderConfirmed']);
        Route::post('/order/deliving', [OrderController::class, 'listOrderDeliving']);
        Route::post('/order/{id}/deliving', [OrderController::class, 'setDeliving']);
        Route::post('/order/{id}/success', [OrderController::class, 'setSuccess']);
    });

    Route::middleware('admin.role:all_order')->group(function () {
        Route::get('/order/all', [HomeController::class, 'allOrder']);
    });

    Route::middleware('admin.role:create_order')->group(function () {
        Route::get('/order/create', [OrderController::class, 'createOrder']);
        Route::post('/order/create', [OrderController::class, 'createOrderPost']);
    });

    Route::middleware('admin.role:confirm_withdraw')->group(function () {
        Route::get('/withdraw/confirm', [HomeController::class, 'confirmWithdraw']);
        Route::post('/withdraw/{id}/accept', [WithdrawController::class, 'accept']);
        Route::post('/withdraw/{id}/cancel', [WithdrawController::class, 'cancel']);
    });

    Route::middleware('admin.role:settings')->group(function () {
        Route::get('/settings', [SettingsController::class, 'home']);
        Route::post('/setting/update/role', [SettingsController::class, 'updateRole']);
    });
});
