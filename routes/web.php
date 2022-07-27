<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

// Authentication...
Route::get('/login/github', [Controllers\Auth\GitHubSocialiteController::class, 'redirect'])->name('login.github');
Route::get('/login/github/callback', [Controllers\Auth\GitHubSocialiteController::class, 'callback'])->name('login.github.callback');

Route::get('/', function () {
    return view('welcome');
})->name('home');
