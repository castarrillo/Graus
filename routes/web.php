<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('google/redirect', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');
