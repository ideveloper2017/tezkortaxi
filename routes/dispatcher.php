<?php
Route::get('/', 'DispatcherController@new_booking')->name('new_booking');
Route::get('/recent-trips', 'DispatcherController@index')->name('index');
Route::get('map', 'DispatcherController@map_index')->name('map.index');
Route::get('map/ajax', 'DispatcherController@map_ajax')->name('map.ajax');
Route::get('/trip_data','DispatcherController@trip_data');
Route::get('/provider_zones','DispatcherController@getZonesWithProvider');
Route::get('/user','DispatcherController@getUserDetail');

Route::post('/map/dirver/routine','DispatcherController@saveDriverZoneEntertime');
Route::post('/discancel/ride', 'DispatcherController@cancel_ride');
Route::post('/assign/company', 'DispatcherController@assignCompany');
Route::post('/request/update','DispatcherController@update_trip');

Route::resource('service', 'Resource\ServiceResource');
Route::resource('corporate_list', 'Resource\CorporateAccountResource');
Route::get('get-locations/{type?}', 'LiveTrip@index')->name('live.index');
Route::get('get-details/{type}/{id}', 'LiveTrip@getDetailsD')->name('live.details');


Route::group(['as' => 'dispatcher.', 'prefix' => 'dispatcher'], function () {
Route::get('/', 'DispatcherController@index')->name('index');
Route::post('/', 'DispatcherController@store')->name('store');
Route::post('/getFare', 'DispatcherController@get_ride_fare')->name('getFare');
Route::get('/trips', 'DispatcherController@trips')->name('trips');
Route::get('/single-trip', 'DispatcherController@singleTrip')->name('singleTrip');
Route::get('/trips/{trip}/{provider}/{type}', 'DispatcherController@assign')->name('assign');
Route::get('/dispatch/trips/{trip}/{provider}/{type}', 'DispatcherController@assign')->name('assign');
Route::get('/users', 'DispatcherController@users')->name('users');
Route::get('/providers', 'DispatcherController@providers')->name('providers');
Route::post('/provider_list', 'DispatcherController@providerList')->name('provider_list');
Route::get('/getlogs','DispatcherController@getlogs');
});


Route::get('password', 'DispatcherController@password')->name('password');
Route::post('password', 'DispatcherController@password_update')->name('password.update');

Route::get('profile', 'DispatcherController@profile')->name('profile');
Route::post('profile', 'DispatcherController@profile_update')->name('profile.update');
Route::get('/openTicket/{type?}', 'DispatcherController@openTicket')->name('openTicket');
Route::get('/closeTicket', 'DispatcherController@closeTicket')->name('closeTicket');
Route::get('/openTicketDetail/{id}', 'DispatcherController@openTicketDetail')->name('openTicketDetail');
Route::patch('/transfer/{id}', 'DispatcherController@transfer')->name('transfer');
Route::get('/test','DispatcherController@test');
 