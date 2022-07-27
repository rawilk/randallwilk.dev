<?php

use Illuminate\Support\Facades\Route;

// Dashboard...
Route::view('/dashboard', 'admin.dashboard.index')->name('dashboard');
