<?php

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [ApiAuthController::class, 'login']);
Route::post('/auth/register', [ApiAuthController::class, 'register']);
Route::post('/auth/token/verify', [ApiAuthController::class, 'verifyToken']);
Route::get('/present/name', [ApiUserController::class, 'presentName']);
