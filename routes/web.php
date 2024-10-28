<?php

declare(strict_types=1);

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

// Authentication...
Route::get('/login/github', [Controllers\Auth\GitHubSocialiteController::class, 'redirect'])
    ->middleware(['signed'])
    ->name('login.github');
Route::get('/login/github/callback', [Controllers\Auth\GitHubSocialiteController::class, 'callback'])->name('login.github.callback');

Route::view('/contact', 'front.pages.contact.index')->name('contact');
Route::view('/', 'front.pages.home.index')->name('home');

Route::prefix('open-source')->group(function () {
    Route::view('/', 'front.pages.open-source.packages')->name('open-source.packages');
    Route::view('/projects', 'front.pages.open-source.projects')->name('open-source.projects');
    Route::view('/support-me', 'front.pages.open-source.support')->name('open-source.support');
});

// Legal...
Route::view('/legal', 'front.pages.legal.index')->name('legal.index');
Route::view('/privacy', 'front.pages.legal.privacy')->name('legal.privacy');
Route::view('/terms', 'front.pages.legal.terms')->name('legal.terms');
Route::view('/disclaimer', 'front.pages.legal.disclaimer')->name('legal.disclaimer');

// Docs...
Route::prefix('/docs')->group(function () {
    Route::get('/', [Controllers\Docs\DocsController::class, 'index'])->name('docs');
    Route::get('/{repository}/{alias?}', [Controllers\Docs\DocsController::class, 'repository'])->name('docs.repository');
    Route::get('/{repository}/{alias}/{slug}', [Controllers\Docs\DocsController::class, 'show'])->name('docs.show')->where('slug', '.*');
});

// Uses...
Route::view('/uses', 'front.pages.uses.index')->name('uses');

// Sitemap...
Route::get('/sitemap', Controllers\Front\SiteMapController::class)->name('sitemap');

// Webauthn...
Route::webauthn();
