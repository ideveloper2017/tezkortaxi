<?php

/*
|--------------------------------------------------------------------------
| Provider Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ProviderController@index')->name('index');
Route::get('/trips', 'ProviderResources\TripController@history')->name('trips');

Route::get('/incoming', 'ProviderController@incoming')->name('incoming');
Route::get('/updateStatus', 'ProviderController@updateStatus')->name('updateStatus');

Route::post('/request/{id}', 'ProviderController@accept')->name('accept');
Route::patch('/request/{id}', 'ProviderController@update')->name('update');
Route::post('/request/{id}/rate', 'ProviderController@rating')->name('rating');
Route::delete('/request/{id}', 'ProviderController@reject')->name('reject');

Route::get('/earnings', 'ProviderController@earnings')->name('earnings');
Route::get('/upcoming', 'ProviderController@upcoming_trips')->name('upcoming');
Route::get('/upcoming/detail', 'ProviderController@upcoming_details')->name('payment.upcoming_detail');
Route::get('/cancel', 'ProviderController@cancel')->name('cancel');

Route::resource('documents', 'ProviderResources\DocumentController');
Route::resource('document', 'ProviderResources\DocumentController');

Route::get('/profile', 'ProviderResources\ProfileController@show')->name('profile.profile');
Route::get('/edit/profile', 'ProviderController@edit_profile')->name('profile.index');
Route::post('/profile', 'ProviderResources\ProfileController@store')->name('profile.update');

Route::get('/location', 'ProviderController@location_edit')->name('location.index');
Route::post('/location', 'ProviderController@location_update')->name('location.update');

Route::get('/profile/password', 'ProviderController@change_password')->name('change.password');
Route::post('/change/password', 'ProviderController@update_password')->name('password.update');

Route::post('/profile/available', 'ProviderController@available')->name('available');


Route::get('/weeklyearning', 'ProviderController@weeklyearning')->name('weeklyearning');
Route::get('/dailyearning', 'ProviderController@yearlyearning')->name('yearlyearning');
Route::get('/mytrips', 'ProviderController@dailyearning')->name('dailyearning');
Route::get('/mytrips/detail', 'ProviderController@dailyearning_detail')->name('dailyearning_detail');

Route::get('/faq', 'ProviderController@faqs')->name('faq');
Route::get('/terms', 'ProviderController@terms')->name('terms');
Route::get('/help', 'ProviderController@helps')->name('help');