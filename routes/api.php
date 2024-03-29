<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\Api\SocialController as ApiSocialController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Controllers\Api\MissionListController as ApiMissionListController;
use App\Http\Controllers\Api\MissionController as ApiMissionController;
use App\Http\Controllers\Api\BanksController as ApiBanksController;
use App\Http\Controllers\Api\MoneyController as ApiMoneyController;
use App\Http\Controllers\Api\EventController as ApiEventController;
use App\Http\Controllers\Api\KycController as ApiKycController;

Route::post('/auth/login', [ApiAuthController::class, 'login']);
Route::post('/__/____', [ApiAuthController::class, 'getPhoneByUsername']);
Route::post('/auth/register', [ApiAuthController::class, 'register']);
Route::post('/auth/forgot', [ApiAuthController::class, 'forgot']);
Route::post('/auth/forgot/confirm', [ApiAuthController::class, 'forgotConfirm']);
Route::post('/auth/forgot/change', [ApiAuthController::class, 'forgotChangePassword']);
Route::post('/auth/token/verify', [ApiAuthController::class, 'verifyToken']);
Route::post('/auth/account/verify', [ApiAuthController::class, 'verifyAccount']);
Route::post('/auth/account/resend-otp', [ApiAuthController::class, 'reSendOtp']);

Route::get('/present/name', [ApiUserController::class, 'presentName']);
Route::get('/products', [ApiProductController::class, 'lists']);
Route::get('/product/{id}', [ApiProductController::class, 'getOne']);
Route::get('/banks', [ApiBanksController::class, 'list']);
Route::get('/value-of-akg', [ApiMoneyController::class, 'getValueOfAkg']);
Route::get('/datetime-countdown', [ApiEventController::class, 'getDatetimeCountdown']);

Route::middleware('api.auth')->group(function () {
    Route::post('/auth/info', [ApiAuthController::class, 'info']);
    Route::get('/user/tree', [ApiUserController::class, 'getTree']);
    Route::get('/user/get-status-join-cashback', [ApiUserController::class, 'checkJoinedCashback']);
    Route::get('/user/tree/{username}', [ApiUserController::class, 'getTreeWithUsername']);
    Route::post('/user/dashboard', [ApiUserController::class, 'getDashboardData']);
    Route::post('/user/withdraw', [ApiUserController::class, 'withdrawRequest']);
    Route::post('/user/withdraw/history', [ApiUserController::class, 'withdrawHistory']);
    Route::post('/user/get_money_can_withdraw', [ApiUserController::class, 'moneyCanWithdraw']);
    Route::get('/user/child/{id}', [ApiUserController::class, 'getChild']);
    Route::post('/telegram/put/message', [ApiSocialController::class, 'pushMessageTelegram']);
    Route::post('/order', [ApiOrderController::class, 'order']);
    Route::post('/order/history', [ApiOrderController::class, 'history']);
    Route::get('/mission-list/{type}', [ApiMissionListController::class, 'list']);
    Route::post('/mission/update', [ApiMissionController::class, 'update']);

    Route::post('/user/update/nomal', [ApiUserController::class, 'updateNomalInfo']);
    Route::post('/user/update/bank', [ApiUserController::class, 'updateBankInfo']);
    Route::get('/user/bank', [ApiUserController::class, 'getBankInfo']);

    Route::post('/user/otp/withdraw', [ApiUserController::class, 'withdrawSendOtp']);
    Route::post('/user/otp/tranfer-akg', [ApiUserController::class, 'transferAkgSendOtp']);

    Route::post('/user/money/history', [ApiMoneyController::class, 'getMoneyHistory']);
    Route::post('/user/akg/history', [ApiMoneyController::class, 'getAkgHistory']);
    Route::post('/user/product_point/history', [ApiMoneyController::class, 'getProductPointHistory']);
    Route::post('/user/money/transfer/akg', [ApiMoneyController::class, 'transferAkg']);
    Route::post('/user/history/transfer/akg', [ApiMoneyController::class, 'transferAkgHistory']);
    Route::post('/user/bonus/history', [ApiUserController::class, 'bonusHistory']);

    Route::post('/event/cashback/join', [ApiEventController::class, 'joinCashback']);

    Route::post('/user/point/check', [ApiMoneyController::class, 'checkPointPayment']);
    Route::post('/user/can/transfer-akg', [ApiUserController::class, 'checkCanTransferAkg']);

    Route::post('/user/kyc', [ApiKycController::class, 'uploadKyc']);
    
    Route::get('/random-lucky-event', [ApiEventController::class, 'getNumberLuckyEvent']);
    Route::post('/random-lucky-event', [ApiEventController::class, 'randomLuckyEvent']);
    Route::post('/random-lucky-event/update', [ApiEventController::class, 'updateLuckyEvent']);
});
