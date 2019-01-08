<?php

Route::get('about-me', 'PagesController@viewPage')->name('about');
Route::get('projects', 'PagesController@viewPage')->name('projects');
Route::get('resume', 'PagesController@viewPage')->name('resume');
Route::get('blog', 'PagesController@viewPage')->name('blog');
Route::get('privacy', 'PagesController@viewPage')->name('privacy');
Route::get('terms', 'PagesController@viewPage')->name('terms');
Route::get('contact', 'PagesController@viewPage')->name('contact');
Route::post('contact', 'ContactController@store')->name('contact.store');

Route::get('project/{project}', 'ProjectsController@index')->name('projects.view');
Route::get('post/{post}', 'PostsController@index')->name('posts.view');
Route::get('category/{category}', 'PostsController@category')->name('posts.category');

Route::get('/', 'HomeController@index')->name('home');
