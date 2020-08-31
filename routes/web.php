<?php

use App\Http\Controllers\DocsController;
use App\Http\Controllers\RedirectDocsDomainController;
use Illuminate\Support\Facades\Route;

Route::domain('docs.randallwilk.dev')->group(static function () {
    Route::get('/{url}', RedirectDocsDomainController::class)
        ->where('url', '.*');
});

Route::view('contact', 'front.pages.contact.index')->name('contact');
Route::view('/', 'front.pages.home.index')->name('home');

Route::prefix('open-source')->group(static function () {
    Route::view('/', 'front.pages.open-source.packages')->name('open-source.packages');
    Route::view('projects', 'front.pages.open-source.projects')->name('open-source.projects');
});

Route::view('legal', 'front.pages.legal.index')->name('legal.index');
Route::view('privacy', 'front.pages.legal.privacy')->name('legal.privacy');
Route::view('disclaimer', 'front.pages.legal.disclaimer')->name('legal.disclaimer');

Route::prefix('docs')->group(static function () {
    Route::get('/', [DocsController::class, 'index'])->name('docs');
    Route::get('/{repository}/{alias?}', [DocsController::class, 'repository'])->name('docs.repository');
    Route::get('/{repository}/{alias}/{slug}', [DocsController::class, 'show'])
        ->name('docs.show')
        ->where('slug', '.*');
});
