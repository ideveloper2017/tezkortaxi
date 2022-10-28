<?php


Route::group(['as' => 'support.', 'prefix' => 'support'], function () {
	 Route::get('/', 'SupportController@index')->name('index');
Route::get('/', 'SupportController@dashboard')->name('index');
Route::get('/dashboard', 'SupportController@dashboard')->name('dashboard');
Route::get('profile', 'SupportController@profile')->name('profile');
Route::post('profile', 'SupportController@profile_update')->name('profile.update');
	
});

// Route::get('/', 'SupportController@index')->name('index');

/*
|--------------------------------------------------------------------------
| CMS Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'SupportController@dashboard')->name('index');
Route::get('/dashboard', 'SupportController@dashboard')->name('dashboard');

 

Route::get('profile', 'SupportController@profile')->name('profile');
Route::post('profile', 'SupportController@profile_update')->name('profile.update');

Route::get('password', 'SupportController@password')->name('password');
Route::post('password', 'SupportController@password_update')->name('password.update');
Route::get('/open-ticket/{type}', 'SupportController@openTicket')->name('openTicket');
Route::get('/close-ticket', 'SupportController@closeTicket')->name('closeTicket');
Route::get('/open-ticket-details/{id}', 'SupportController@openTicketDetails')->name('openTicketDetails');
Route::patch('/transfer/{id}', 'SupportController@transfer')->name('transfer');
Route::resource('bank', 'Resource\SupportBankResource');
Route::resource('new_account', 'Resource\SupportBankResource@new_account');
Route::get('approved_account', 'Resource\SupportBankResource@approved_account');
Route::get('new_withdraw', 'Resource\SupportBankResource@new_withdraw');
Route::get('approved_withdraw', 'Resource\SupportBankResource@approved_withdraw');
Route::get('disapproved_withdraw', 'Resource\SupportBankResource@disapproved_withdraw');
