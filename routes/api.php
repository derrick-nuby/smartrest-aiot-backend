<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;


Route::get('/', fn () => ['message' => 'Welcome to SmartRest IoT API']);
Route::get('/v1', fn () => ['message' => 'SmartRest IoT API v1 - Ready to serve your requests']);

Route::apiResource('products', ProductController::class);

Route::apiResource('users', UserController::class);