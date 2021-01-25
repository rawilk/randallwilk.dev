<?php

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\GithubSocialiteController;
use App\Http\Controllers\Auth\ImpersonationController;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\RedirectDocAssetsController;
use App\Http\Controllers\RedirectDocsDomainController;
use App\Http\Controllers\RedirectVueContextDomainController;
use App\Http\Controllers\UserProfileController;
use App\Http\Livewire;
use Illuminate\Support\Facades\Route;

Route::domain('docs.randallwilk.dev')->group(static function () {
    Route::get('/{url}', RedirectDocsDomainController::class)
        ->where('url', '.*');
});

Route::domain('vue-context.com')->group(static function () {
    Route::get('/{url}', RedirectVueContextDomainController::class)
        ->where('url', '.*');
});

Route::view('contact', 'front.pages.contact.index')->name('contact');
Route::view('/', 'front.pages.home.index')->name('home');

// Authentication...
Route::middleware('guest')->group(static function () {
    Route::get('/login', Livewire\Auth\Login::class)->name('login');
});

Route::get('/password/reset', Livewire\Auth\Passwords\Email::class)
    ->name('password.request');

Route::get('/password/reset/{token}', Livewire\Auth\Passwords\Reset::class)
    ->name('password.reset');

Route::middleware('auth')->group(static function () {
    Route::post('/logout', \App\Http\Controllers\Auth\LogoutController::class)
        ->name('logout');

    Route::get('/impersonate/leave', [ImpersonationController::class, 'leave'])
        ->name('impersonate.leave');
});

Route::get('/login/github', [GithubSocialiteController::class, 'redirect'])->name('login.github');
Route::get('/login/github/callback', [GithubSocialiteController::class, 'callback'])->name('login.github.callback');

Route::prefix('open-source')->group(static function () {
    Route::view('/', 'front.pages.open-source.packages')->name('open-source.packages');
    Route::view('projects', 'front.pages.open-source.projects')->name('open-source.projects');
});

Route::view('legal', 'front.pages.legal.index')->name('legal.index');
Route::view('privacy', 'front.pages.legal.privacy')->name('legal.privacy');
Route::view('disclaimer', 'front.pages.legal.disclaimer')->name('legal.disclaimer');

// User profile...
Route::middleware('auth')->group(static function () {
    Route::get('/user/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/user/profile/authentication', [UserProfileController::class, 'authentication'])->name('profile.authentication');
});

Route::prefix('docs')->group(static function () {
    Route::get('/', [DocsController::class, 'index'])->name('docs');
    Route::get('/{repository}/{alias?}', [DocsController::class, 'repository'])->name('docs.repository');

    // Kind of a dirty workaround for now to redirect doc assets from "/docs" to "/doc_files" until I can figure out why
    // the live server is serving a 403 error when the public directory is the same as this endpoint.
    Route::get('/{repository}/{alias}/images/{path}', RedirectDocAssetsController::class)->where('path', '.*');
    Route::get('/{repository}/{alias}/scripts/{path}', RedirectDocAssetsController::class)->where('path', '.*');

    Route::get('/{repository}/{alias}/{slug}', [DocsController::class, 'show'])
        ->name('docs.show')
        ->where('slug', '.*');
});

// Admin routes...
Route::middleware('admin')
    ->prefix('admin')
    ->as('admin.')
    ->group(static function () {
        // Dashboard...
        Route::view('dashboard', 'admin.dashboard.index')->name('dashboard');

        // Repositories...
        Route::get('/repositories', Livewire\Admin\Repositories\Index::class)->name('repositories');
        Route::get('/repositories/{repository:name}', [\App\Http\Controllers\Admin\RepositoriesController::class, 'show'])
            ->middleware('can:edit,repository')
            ->name('repositories.show');

        // Users...
        Route::get('/users', Livewire\Admin\Users\Index::class)->name('users');
        Route::get('/users/create', Livewire\Admin\Users\Create::class)->name('users.create');
        Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.edit')->middleware('can:edit,user');
    });
