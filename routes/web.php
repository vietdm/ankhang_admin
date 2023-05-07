<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home']);
Route::get('/order/accept/{id}', [OrderController::class, 'accept']);

Route::get('verify-forgot');
