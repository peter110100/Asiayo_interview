<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

// API 路由
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{id}', [OrderController::class, 'show']);