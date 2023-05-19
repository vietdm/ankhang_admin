<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WithdrawController;
use App\Models\Configs;
use App\Models\HistoryBonus;
use App\Models\LevelUpCondition;
use App\Models\Orders;
use App\Models\TotalAkgLog;
use App\Models\UserMoney;
use App\Models\Users;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('auth0/login', [AuthController::class, 'login']);
// Route::get('_reset', function () {
//     //set total akg = 90000000
//     Configs::setDouble('total_akg', 60000000);

//     //clear level up condition
//     DB::statement('TRUNCATE table level_up_condition;');
//     DB::statement('ALTER table level_up_condition AUTO_INCREMENT = 1;');

//     //clear users
//     foreach (Users::all() as $user) {
//         $user->total_buy = 0;
//         $user->package_joined = null;
//         $user->level = 'nomal';
//         $user->save();
//     }

//     //clear user money
//     foreach (UserMoney::all() as $userMoney) {
//         $userMoney->money_bonus = 0;
//         $userMoney->akg_point = 0;
//         $userMoney->cashback_point = 0;
//         $userMoney->save();
//     }

//     //clear history bonus
//     DB::statement('TRUNCATE table history_bonus;');
//     DB::statement('ALTER table history_bonus AUTO_INCREMENT = 1;');

//     //clear all order dont pay
//     foreach (Orders::where(['payed' => 0])->orWhere(['status' => 4])->get() as $oo) {
//         $oo->delete();
//     }

//     //clear withdraw cancel
//     foreach (Withdraw::where(['status' => 3])->get() as $ww) {
//         $ww->delete();
//     }

//     //reset log akg
//     DB::statement('TRUNCATE table total_akg_log;');
//     DB::statement('ALTER table total_akg_log AUTO_INCREMENT = 1;');
// });
// Route::get('_order', function () {
//     $withdraw = [];
//     foreach (Withdraw::all() as $ww) {
//         $withdraw[$ww->user_id] = $ww->money;
//     }

//     $userMoney = [];
//     foreach (Orders::whereStatus(1)->get() as $order) {
//         $userId = $order->user_id;
//         $order->accept();

//         if (!isset($withdraw[$userId])) continue;

//         if (!isset($userMoney[$userId])) {
//             $userMoney[$userId] = UserMoney::whereUserId($userId)->first();
//         }

//         $userMoney[$userId]->money_bonus -= $withdraw[$userId];
//         if ($userMoney[$userId]->money_bonus < 0) {
//             $userMoney[$userId]->money_bonus = 0;
//         }
//         $userMoney[$userId]->save();
//     }
// });
// Route::get('_akg', function () {
//     $users = Users::with(['user_money'])->get();
//     $userSave = [];

//     foreach ($users as $user) {
//         $userSave[$user->id] = $user;
//     }

//     foreach ($users as $user) {
//         if (!isset($userSave[$user->parent_id])) continue;
//         if ($userSave[$user->parent_id]->total_buy == 0) {
//             $userSave[$user->parent_id]->user_money->akg_point += 1;
//             TotalAkgLog::insert([
//                 'user_id' => $user->parent_id,
//                 'date' => Carbon::now()->format('Y-m-d H:i:s'),
//                 'amount' => 1,
//                 'content' => 'Chi trả giới thiệu. Khách chưa vào gói'
//             ]);
//         } else {
//             $userSave[$user->parent_id]->user_money->akg_point += 2;
//             TotalAkgLog::insert([
//                 'user_id' => $user->parent_id,
//                 'date' => Carbon::now()->format('Y-m-d H:i:s'),
//                 'amount' => 1,
//                 'content' => 'Chi trả giới thiệu. Khách đã vào gói'
//             ]);
//         }
//         $userSave[$user->parent_id]->user_money->save();
//     }
// });
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
