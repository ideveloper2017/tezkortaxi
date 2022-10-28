<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/ 

Route::get('/', 'AdminController@dashboard')->name('index');
Route::get('/notification', 'AdminController@notification')->name('notification');
Route::get('/dashboard', 'AdminController@dashboard')->name('dashboard');
 Route::get('/searchingajax', 'AdminController@searchingajax');
 Route::get ('/ajaxforofflineprovider' , 'AdminController@ajaxforofflineprovider');

Route::get('/airport', 'AdminController@airport')->name('airport');
Route::get('/delivery', 'AdminController@delivery')->name('delivery');
Route::get('/rental', 'AdminController@rental')->name('rental');
Route::get('/surge', 'AdminController@dashboard')->name('surge');
Route::get('cabAllocation', 'AdminController@cabAllocation')->name('cabAllocation');
Route::get('cabAllocation_update', 'AdminController@cabAllocation_update')->name('cabAllocation_update');
Route::get('cabAllocation_edit/{id}', 'AdminController@cabAllocation_edit')->name('cabAllocation_edit');

Route::get('allocation_list', 'AdminController@allocation_list')->name('allocation_list');
 Route::get('/translation',  'AdminController@translation')->name('translation');
Route::get('/heatmap', 'AdminController@heatmap')->name('heatmap');
Route::get('/translation',  'AdminController@translation')->name('translation');

Route::group(['as' => 'dispatcher.', 'prefix' => 'dispatcher'], function () {
	Route::get('/', 'DispatcherController@index')->name('index');
	Route::post('/', 'DispatcherController@store')->name('store');
	Route::get('/trips', 'DispatcherController@trips')->name('trips');
	Route::get('/trips/{trip}/{provider}', 'DispatcherController@assign')->name('assign');
	Route::get('/users', 'DispatcherController@users')->name('users');
	Route::get('/providers', 'DispatcherController@providers')->name('providers');
});

Route::get('/zone/getCountry','Resource\ZoneResource@getCountry')->name('getcountry');
Route::get('/zone/getState','Resource\ZoneResource@getState')->name('getstate');
Route::get('/zone/getCity','Resource\ZoneResource@getCity')->name('getcity');
Route::get('/pushnotification/testUrl', 'PushNotificationResource@getZoneId');
Route::get('/pushnotification/getZoneProviders/{id}/{typeid}', 'Resource\PushNotificationResource@getZonesProviders')->name('pushzoneproviders');
Route::get('/pushnotification/getProvidersAndUsers/{typeid}', 'Resource\PushNotificationResource@getProvidersAndUsers')->name('getProvidersAndUsers');

Route::get('/pushnotification/getZones', 'Resource\PushNotificationResource@getZones')->name('allzones');

Route::resource('zone', 'Resource\ZoneResource');
Route::resource('pushnotification', 'Resource\PushNotificationResource');
Route::resource('country', 'Resource\CountryResource');
Route::resource('state', 'Resource\StateResource');
Route::resource('city', 'Resource\CityResource');
Route::resource('location', 'Resource\LocationResource');
Route::resource('user', 'Resource\UserResource');
Route::resource('dispatch-manager', 'Resource\DispatcherResource');
Route::resource('account-manager', 'Resource\AccountResource');
Route::resource('fleet', 'Resource\FleetResource');
Route::resource('provider', 'Resource\ProviderResource');
Route::resource('document', 'Resource\DocumentResource');
Route::resource('service', 'Resource\ServiceResource');
Route::resource('blog', 'Resource\BlogAdminResource');
Route::resource('allocation', 'Resource\ServiceResource@allocation');
Route::post('service_update/{id}', 'AdminController@service_update')->name('update');

Route::get('faresettings', 'AdminController@fare_settings')->name('fare_settings');
Route::post('faresettings/store', 'AdminController@fare_settings_store')->name('fare.settings.store');
Route::get('settings/create', 'AdminController@fare_settings_create')->name('fare.settings.create');
Route::post('destory_fare', 'AdminController@destory_fare')->name('destory_fare');
Route::delete('delete_fare', 'AdminController@deleteFare')->name('fare.settings.destroy');
Route::delete('destory_pkg', 'AdminController@deletePKG')->name('fare.settings.deletePKG');
Route::get('edit_fare/{id}', 'AdminController@editFare')->name('fare.settings.edit');
Route::post('edit_fare_action', 'AdminController@editFareAction');
Route::post('addpeakAnight', 'AdminController@addpeakAnight')->name('peakNight');
Route::post('settings/addpeakAnight', 'AdminController@addpeakAnight');

Route::resource('bank', 'Resource\BankResource');
Route::resource('new_account', 'Resource\BankResource@new_account');
Route::get('approved_account', 'Resource\BankResource@approved_account');
Route::get('new_withdraw', 'Resource\BankResource@new_withdraw');
Route::get('approved_withdraw', 'Resource\BankResource@approved_withdraw');
Route::get('disapproved_withdraw', 'Resource\BankResource@disapproved_withdraw');

Route::resource('faqs', 'Resource\AdminFaqResource');    
Route::resource('how-it-work', 'Resource\AdminHowitWorkResource');    
Route::resource('page', 'Resource\PageAdminResource');
Route::resource('cms-manager', 'Resource\CmsResource');
Route::resource('support-manager', 'Resource\SupportResource');
Route::get('/support/open-ticket', 'Resource\SupportResource@openTicket')->name('openTicket');
Route::get('/support/close-ticket', 'Resource\SupportResource@closeTicket')->name('closeTicket');
Route::get('/support/open-ticket-details/{id}', 'Resource\SupportResource@openTicketDetails')->name('openTicketDetails');
Route::patch('/support/transfer/{id}', 'Resource\SupportResource@transfer')->name('transfer');
Route::resource('crm-manager', 'Resource\CrmResource');

Route::get('/crm/open-ticket/{type?}', 'Resource\CrmResource@openTicket')->name('openTicket');
Route::get('/crm/open-ticket-details/{id}', 'Resource\CrmResource@openTicketDetails')->name('openTicketDetails');
Route::patch('/crm/transfer/{id}', 'Resource\CrmResource@transfer')->name('transfer');
Route::get('/crm/close-ticket', 'Resource\CrmResource@closeTicket')->name('closeTicket');

Route::get('promocode/user', 'Resource\PromocodeResource@userPromoCode')->name('promocode.users');
Route::get('promocode/promocodes', 'Resource\PromocodeResource@getPromoCodes')->name('promocodes');
Route::get('promocode/promocodeusage', 'Resource\PromocodeResource@getPromocodeUser')->name('promocodeusage');
Route::resource('promocode', 'Resource\PromocodeResource');
Route::resource('testimonial', 'Resource\TestimonialResource');

Route::group(['as' => 'provider.'], function () {
    Route::get('review/provider', 'AdminController@provider_review')->name('review');
    Route::get('provider/{id}/approve', 'Resource\ProviderResource@approve')->name('approve');
    Route::get('provider/{id}/disapprove', 'Resource\ProviderResource@disapprove')->name('disapprove');
    Route::get('provider/{id}/request', 'Resource\ProviderResource@request')->name('request');
    Route::get('provider/{id}/statement', 'Resource\ProviderResource@statement')->name('statement');
    Route::resource('provider/{provider}/document', 'Resource\ProviderDocumentResource');
    Route::delete('provider/{provider}/service/{document}', 'Resource\ProviderDocumentResource@service_destroy')->name('document.service');
    Route::get('provider/{provider}/document/{document}/upload', 'Resource\ProviderDocumentResource@get_provider_document_upload');
	Route::post('provider/{provider}/document/{document}/upload', 'Resource\ProviderDocumentResource@provider_document_upload');
	Route::get('provider/{provider}/document/{document}/update', 'Resource\ProviderDocumentResource@edit_provider_document_upload');
	Route::post('provider/{provider}/document/{document}/update', 'Resource\ProviderDocumentResource@update_provider_document_upload');
	
});

Route::get('review/user', 'AdminController@user_review')->name('user.review');
Route::get('user/{id}/request', 'Resource\UserResource@request')->name('user.request');

Route::get('map', 'AdminController@map_index')->name('map.index');
Route::get('map/ajax', 'AdminController@map_ajax')->name('map.ajax');

Route::get('settings', 'AdminController@settings')->name('settings');
Route::post('settings/store', 'AdminController@settings_store')->name('settings.store');
Route::get('settings/payment', 'AdminController@settings_payment')->name('settings.payment');
Route::post('settings/payment', 'AdminController@settings_payment_store')->name('settings.payment.store');

Route::get('profile', 'AdminController@profile')->name('profile');
Route::post('profile', 'AdminController@profile_update')->name('profile.update');
Route::get('/contact', 'AdminController@contact')->name('contact');
Route::delete('/destroy/{id}', 'AdminController@destroy')->name('destroy');

Route::get('password', 'AdminController@password')->name('password');
Route::post('changeprovidorpassword', 'AdminController@changeprovidorpassword')->name('changeprovidorpassword');
Route::post('changeuserpassword', 'AdminController@changeuserpassword')->name('changeuserpassword');
Route::post('password', 'AdminController@password_update')->name('password.update');
Route::get('payment', 'AdminController@payment')->name('payment');
// statements
Route::get('/statement', 'AdminController@statement')->name('ride.statement');
Route::get('/statement/provider', 'AdminController@statement_provider')->name('ride.statement.provider');
Route::get('/statement/today', 'AdminController@statement_today')->name('ride.statement.today');
Route::get('/statement/monthly', 'AdminController@statement_monthly')->name('ride.statement.monthly');
Route::get('/statement/yearly', 'AdminController@statement_yearly')->name('ride.statement.yearly');

// Static Pages - Post updates to pages.update when adding new static pages.

Route::get('/help', 'AdminController@help')->name('help');
Route::get('/privacy', 'AdminController@privacy')->name('privacy');
Route::post('/pages', 'AdminController@pages')->name('pages.update');

Route::get('/faq', 'Resource\FAQController@faqs');
Route::post('/faq', 'Resource\FAQController@create');

Route::get('/terms', 'Resource\TermsConditionController@terms');
Route::post('/terms', 'Resource\TermsConditionController@create');



Route::resource('requests', 'Resource\TripResource');

Route::get('scheduled', 'Resource\TripResource@scheduled')->name('requests.scheduled');
Route::get('cancelTrip', 'Resource\TripResource@cancelTrip')->name('requests.cancel');
Route::get('completedTrip', 'Resource\TripResource@completedTrip')->name('requests.completed');


Route::get('push', 'AdminController@push_index')->name('push.index');
Route::post('push', 'AdminController@push_store')->name('push.store');

Route::get('get-locations/{type?}', 'LiveTrip@index')->name('live.index');
Route::get('get-details/{type}/{id}', 'LiveTrip@getDetails')->name('live.details');

