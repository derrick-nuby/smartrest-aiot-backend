<?php

use Illuminate\Support\Facades\Route;

// Web routes - only for web interface
Route::get('/', function () {
    return view('welcome');
});
