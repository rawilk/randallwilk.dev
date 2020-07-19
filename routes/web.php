<?php

use Illuminate\Support\Facades\Route;

Route::redirect('about', '/');

Route::view('/', 'pages.home')->name('home');
Route::view('projects', 'pages.projects')->name('projects');
Route::view('contact', 'pages.contact')->name('contact');
Route::view('uses', 'pages.uses')->name('uses');
