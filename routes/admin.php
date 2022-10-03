<?php

use App\Enums\PermissionEnum;
use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

// Dashboard...
Route::view('/dashboard', 'admin.dashboard.index')->name('dashboard');

// Repositories...
Route::prefix('/repositories')
    ->middleware('can:' . PermissionEnum::REPOSITORIES_MANAGE->value)
    ->as('repositories.')
    ->group(function () {
        Route::view('/', 'admin.repositories.index.index')->name('index');
        Route::get('/{repository:name}', Controllers\Admin\ShowRepositoryController::class)
            ->middleware('can:edit,repository')
            ->name('show');
    });
