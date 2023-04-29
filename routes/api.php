<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\Api\SocialController as ApiSocialController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;

Route::post('/auth/login', [ApiAuthController::class, 'login']);
Route::post('/auth/register', [ApiAuthController::class, 'register']);
Route::post('/auth/token/verify', [ApiAuthController::class, 'verifyToken']);
Route::get('/present/name', [ApiUserController::class, 'presentName']);
Route::get('/products', [ApiProductController::class, 'lists']);
Route::post('/telegram/put/message', [ApiSocialController::class, 'pushMessageTelegram']);
Route::post('/order', [ApiOrderController::class, 'order']);
