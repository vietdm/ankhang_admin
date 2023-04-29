<?php

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [ApiAuthController::class, 'login']);
Route::put('auth/register', [ApiAuthController::class, 'register']);
