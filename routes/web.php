<?php
/*
|--------------------------------------------------------------------------
| User Authentication Routes
|--------------------------------------------------------------------------
*/

use App\Models\ServiceType;
use App\Models\Testimonial;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;

// Get Route For Show Payment Form
Route::get('paywithrazorpay', 'RazorpayController@payWithRazorpay')->name('paywithrazorpay');
Route::get('payThankYou', 'RazorpayController@payThankYou')->name('payThankYou');
// Post Route For Makw Payment Request
Route::post('razorpay_payment', 'RazorpayController@payment')->name('payment');
Auth::routes();

Route::get('auth/facebook', 'Auth\SocialLoginController@redirectToFaceBook');
Route::get('auth/facebook/callback', 'Auth\SocialLoginController@handleFacebookCallback');
Route::get('auth/google', 'Auth\SocialLoginController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\SocialLoginController@handleGoogleCallback');
Route::post('account/kit', 'Auth\SocialLoginController@account_kit')->name('account.kit');
//Route::get('/searchingajax', 'AdminController@searchingajax');
/*
|--------------------------------------------------------------------------
| Provider Authentication Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'provider'], function () {

    Route::get('auth/facebook', 'Auth\SocialLoginController@providerToFaceBook');
    Route::get('auth/google', 'Auth\SocialLoginController@providerToGoogle');

    Route::get('/login', 'ProviderAuth\LoginController@showLoginForm')->middleware('accesspage');
    // Route::get('/login', 'ProviderAuth\LoginController@showLoginForm');
    // Route::get('/login', 'ProviderAuth\LoginController@login');
    Route::post('/login', 'ProviderAuth\LoginController@login');
    Route::post('/logout', 'ProviderAuth\LoginController@logout');

    Route::get('/register', 'ProviderAuth\RegisterController@showRegistrationForm')->middleware('accesspage');
    Route::post('/register', 'ProviderAuth\RegisterController@register');

    Route::post('/password/email', 'ProviderAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'ProviderAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'ProviderAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'ProviderAuth\ResetPasswordController@showResetForm');
    Route::post('/password/update', 'CommonController@provider_password_update');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'admin'], function () {
    Route::get('/searchingajax', 'CommonController@searchingajax');
    Route::get ('/ajaxforofflineprovider' , 'CommonController@ajaxforofflineprovider');
    Route::get ('/offnotificationtoprovider' , 'SendPushNotification@offnotificationtoprovider');

    Route::get ('/provider-document-expiry-notification' , 'CommonController@providerDocumentExpiryNotification');
    Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->middleware('accesspage');
    Route::post('/login', 'AdminAuth\LoginController@login');
    Route::post('/logout', 'AdminAuth\LoginController@logout');

    Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});

/*
|--------------------------------------------------------------------------
| Dispatcher Authentication Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'dispatcher'], function () {

  Route::get('/login', 'DispatcherAuth\LoginController@showLoginForm')->middleware('accesspage');
  Route::post('/login', 'DispatcherAuth\LoginController@login');
  Route::post('/logout', 'DispatcherAuth\LoginController@logout');

  Route::post('/password/email', 'DispatcherAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'DispatcherAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'DispatcherAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'DispatcherAuth\ResetPasswordController@showResetForm');
});

/*
|--------------------------------------------------------------------------
| Fleet Authentication Routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => 'fleet'], function () {
  Route::get('/login', 'FleetAuth\LoginController@showLoginForm')->middleware('accesspage');
  Route::post('/login', 'FleetAuth\LoginController@login');
  Route::post('/logout', 'FleetAuth\LoginController@logout');

  Route::post('/password/email', 'FleetAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'FleetAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'FleetAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'FleetAuth\ResetPasswordController@showResetForm');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('lang/{locale}', 'LocalizationController@index');
Route::get('/', function () {
    $services   = ServiceType::all();
    $index   = Page::where('en_meta_keys','index')->get();
    $testimonials = Testimonial::all();

    return view('index', compact(['services','testimonials','index']) );
});

Route::post('/get_fare', 'AjaxHandlerController@estimated_fare')->name('getfare');
Route::post('/saveLocationTemp', 'AjaxHandlerController@saveLocationTemp');

Route::post('/locale', 'CommonController@locale' );
Route::get('/fare_estimate', 'CommonController@fare_estimate');
Route::get('/helppage', 'CommonController@helpPage');


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| My Files
|--------------------------------------------------------------------------
*/
//User sign in

Route::get('/PassengerSignin', 'SignInControlller@passengerSignin')->middleware('accesspage');

Route::get('/faq', 'HomeController@faqs');
Route::get('/terms', 'HomeController@terms');
Route::get('/help', 'HomeController@helps');

Route::get('/dashboard', 'HomeController@index')->name('dashboard');
Route::get('/mytrips', 'HomeController@mytrips');
Route::get('/mytrips/detail', 'HomeController@mytrips_details');

// user profiles
Route::get('/profile', 'HomeController@profile');
Route::get('/edit/profile', 'HomeController@edit_profile');
Route::post('/profile', 'HomeController@update_profile');

// update password
Route::get('/change/password', 'HomeController@change_password');
Route::post('/change/password', 'HomeController@update_password');
Route::post('/password/update', 'CommonController@password_update');
// ride
Route::get('/confirm/ride', 'RideController@confirm_ride');
Route::post('/create/ride', 'RideController@create_ride');
Route::post('/cancel/ride', 'RideController@cancel_ride');
Route::get('/onride', 'RideController@onride');
Route::post('/payment', 'PaymentController@payment');
Route::post('/rate', 'RideController@rate');


// PromoCodes
Route::post('/apply_promo_code', 'AjaxHandlerController@applyPromoCodeOnEstimatedFare');

Route::get('/service_types', 'Resource\ServiceResource@index');
Route::post('/get_fare', 'AjaxHandlerController@estimated_fare');
Route::post('/get_fare_yandex', 'AjaxHandlerController@estimated_fare_yandex');

// status check
Route::get('/status', 'RideController@status');

// trips
Route::get('/trips', 'HomeController@trips')->name('trips');
Route::get('/upcoming/trips', 'HomeController@upcoming_trips');
Route::get('/upcoming/trips/detail', 'HomeController@upcoming_trips_details');

// wallet
Route::get('/wallet', 'HomeController@wallet');
Route::post('/add/money', 'PaymentController@add_money');

// payment
Route::get('/payment', 'HomeController@payment');
Route::get('/payment/paypal/status', ['middleware' => 'auth', 'uses' => 'PaymentController@getPaymentStatus'])->name('paypalbookingstatus');
Route::get('/payment/paypal/denied', ['middleware' => 'auth', 'uses' => 'PaymentController@payWithpaypalReject'])->name('paypalbookingreject');
// card
Route::resource('card', 'Resource\CardResource');

Route::get('card/delete/{id}', ['as' => 'card.delete', 'uses' => 'Resource\CardResource@destroy']);

Route::resource('paypal', 'Resource\PaypalResource');
Route::get('paypal/delete/{id}', ['as' => 'paypal.delete', 'uses' => 'Resource\PaypalResource@destroy']);
// promotions
Route::get('/promotions', 'HomeController@promotions_index')->name('promocodes.index');
Route::post('/promotions', 'HomeController@promotions_store')->name('promocodes.store');

Route::group(['prefix' => 'account'], function () {
  Route::get('/login', 'AccountAuth\LoginController@showLoginForm');
    Route::post('/login', 'AccountAuth\LoginController@login');
    Route::post('/logout', 'AccountAuth\LoginController@logout');

    Route::get('/register', 'AccountAuth\RegisterController@showRegistrationForm');
    Route::post('/register', 'AccountAuth\RegisterController@register');

    Route::post('/password/email', 'AccountAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'AccountAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'AccountAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'AccountAuth\ResetPasswordController@showResetForm');
});


//common pages
// Route::get('/reset', 'CommonController@reset')->name('reset');

Route::get('/support/complaint', 'CommonController@complaint')->name('complaints');
Route::post('/contact-us', 'CommonController@contact')->name('contact.us');
Route::post('/ajax-handler/contact', 'CommonController@sendMessage')->name('contact');
Route::post('/ajax-handler/complaint', 'CommonController@complaint_form')->name('complaint');
Route::get('/contact_us', 'CommonController@contact_us')->name('contact_us');
Route::get('/blogs', 'CommonController@blogs')->name('blog.all');
Route::get('/blog/{id}','CommonController@blog_detail')->name('blogdetail');
Route::get('/lost-item', 'CommonController@lost_item')->name('lost_item');
Route::post('/ajax-handler/lost-item', 'CommonController@lostItemForm')->name('lost_item_form');

Route::get('/user', 'CommonController@user');
Route::get('/driver', 'CommonController@driver');
Route::get('/cities', 'CommonController@cities');
Route::get('/country/{type}', 'CommonController@getCountry');
Route::PATCH('/paymentmode', 'HomeController@save_payment_mode' );
Route::get('/how_it_works', 'CommonController@how_it_works');

Route::get('/help', 'CommonController@help');
// Route::get('/login', 'SignInControlller@passengerSignin');
Route::get('/driver_story', 'CommonController@driver_story');
Route::get('/calculate_price', 'CommonController@calculate_price');
Route::get('/download_page', 'CommonController@download_page');
Route::get('/stories', 'CommonController@stories');
Route::get('/ride_overview', 'CommonController@ride_overview');
Route::get('/ride_safety', 'CommonController@ride_safety');
Route::get('/airports', 'CommonController@airports');
Route::get('/drive_overview', 'CommonController@drive_overview');
Route::get('/requirements', 'CommonController@requirements');
Route::get('/driver_app', 'CommonController@driver_app');
Route::get('/vehicle_solutions', 'CommonController@vehicle_solutions');
Route::get('/drive_safety', 'CommonController@drive_safety');
Route::get('/local', 'CommonController@local');
Route::get('/mylift/{type}', 'CommonController@mylift');
// Route::get('/myliftxl', 'CommonController@myliftxl');
// Route::get('/myliftxll', 'CommonController@myliftxll');
// Route::get('/myliftgox', 'CommonController@myliftgox');
// Route::get('/terms_condition', 'CommonController@terms');
Route::get('/about-us', 'CommonController@about_us');
Route::get('/why-us', 'CommonController@why_us');
Route::get('/privacy-policy', 'CommonController@privacy');
Route::get('/refund-policy', 'CommonController@refund_policy');
// Route::get('/fee_estimation', 'CommonController@fee_estimation');
// Route::get('/help', 'CommonController@help');
Route::get('/terms-conditions', 'CommonController@terms_condition');

Route::group(['prefix' => 'cms'], function () {
    Route::get('/login', 'CmsAuth\LoginController@showLoginForm');
    Route::post('/login', 'CmsAuth\LoginController@login');
    Route::post('/logout', 'CmsAuth\LoginController@logout');

    Route::post('/password/email', 'CmsAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'CmsAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'CmsAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'CmsAuth\ResetPasswordController@showResetForm');
});
/*
|--------------------------------------------------------------------------
| Crm Authentication Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'crm'], function () {
    Route::get('/login', 'CrmAuth\LoginController@showLoginForm');
    Route::post('/login', 'CrmAuth\LoginController@login');
    Route::post('/logout', 'CrmAuth\LoginController@logout');

    Route::post('/password/email', 'CrmAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'CrmAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'CrmAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'CrmAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'support'], function () {
    Route::get('/login', 'SupportAuth\LoginController@showLoginForm');
    Route::post('/login', 'SupportAuth\LoginController@login');
    Route::post('/logout', 'SupportAuth\LoginController@logout');

    Route::post('/password/email', 'SupportAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'SupportAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'SupportAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'SupportAuth\ResetPasswordController@showResetForm');
});
