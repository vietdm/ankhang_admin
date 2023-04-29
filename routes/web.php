<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/', function () {
    return response()->json(['status' => 'OK']);
});

Route::post('auth/login', [AuthController::class, 'login']);
Route::put('auth/register', [AuthController::class, 'register']);
