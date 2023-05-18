<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WithdrawController;
use App\Models\Configs;
use App\Models\HistoryBonus;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('auth0/login', [AuthController::class, 'login']);
Route::get('test', function () {
    // $users = [];
    // foreach (HistoryBonus::whereFromUserId(108)->get() as $bonus) {
    //     if (!isset($users[$bonus->user_id])) {
    //         $users[$bonus->user_id] = Users::with(['user_money'])->whereId($bonus->user_id)->first();
    //     }
    //     $users[$bonus->user_id]->user_money->money_bonus -= $bonus->money_bonus;
    //     $users[$bonus->user_id]->user_money->save();
    //     $bonus->delete();
    // }
});
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
