<?php

Route::get('/', 'CmsController@index')->name('index');

/*
|--------------------------------------------------------------------------
| CMS Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'CmsController@dashboard')->name('index');
Route::get('/dashboard', 'CmsController@dashboard')->name('dashboard');

Route::resource('blog', 'Resource\BlogResource');
Route::resource('page', 'Resource\PageResource');
Route::resource('faq', 'Resource\FaqResource');
Route::resource('how-it-work', 'Resource\HowitWorkResource');
Route::resource('testimonial', 'Resource\TestimonialCmsResource');

Route::get('profile', 'CmsController@profile')->name('profile');
Route::post('profile', 'CmsController@profile_update')->name('profile.update');

Route::get('password', 'CmsController@password')->name('password');
Route::post('password', 'CmsController@password_update')->name('password.update');
Route::get('/translation',  'CmsController@translation')->name('translation');
Route::resource('email', 'Resource\EmailResource');
// Route::get('/faq', 'CmsController@helpPage');
Route::delete('/faq_remove/{id}', 'CmsController@faq_remove')->name('faq_remove');