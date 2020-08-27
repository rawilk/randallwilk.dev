<?php

use Illuminate\Support\Facades\Route;

Route::view('contact', 'front.pages.contact.index')->name('contact');
Route::view('/', 'front.pages.home.index')->name('home');

Route::prefix('open-source')->group(static function () {
    Route::view('/', 'front.pages.open-source.packages')->name('open-source.packages');
    Route::view('projects', 'front.pages.open-source.projects')->name('open-source.projects');
});

Route::view('legal', 'front.pages.legal.index')->name('legal.index');
Route::view('privacy', 'front.pages.legal.privacy')->name('legal.privacy');
Route::view('disclaimer', 'front.pages.legal.disclaimer')->name('legal.disclaimer');
