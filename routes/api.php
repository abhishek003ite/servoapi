<?php

// Please ask to see the API doc for details on how this API was designed

/**
 * Partner routes that do not require authentication
 */
Route::prefix('partners')->group(function() {
    Route::post('/login', 'Partner\PartnerLoginController@login');
    Route::post('/register-email', 'Partner\PartnerRegisterController@registerEmail');
    Route::post('/verify-email', 'Partner\PartnerRegisterController@verifyEmail');
    Route::post('/forgot-password', 'Partner\PartnerLoginController@forgotPassword');
    Route::post('/reset-password', 'Partner\PartnerLoginController@resetPassword');
    Route::get('/{id}/ratings', 'Partner\PartnerRatingController@allRatings');
});

/**
 * Partner routes that require authentication
 */

Route::group(['prefix' => 'partners', 'middleware' => ['partner.token', 'partner.active']], function()  {
    Route::post('/{id}/send-otp', 'Partner\PartnerOtpController@sendOtp');
    Route::post('/{id}/verify-otp', 'Partner\PartnerOtpController@verifyOtp');
    Route::post('/{id}/basic-details', 'Partner\PartnerRegisterController@updatePartnerDetails');
    Route::get('/{id}/basic-details', 'Partner\PartnerRegisterController@getPartnerDetails');

    Route::post('/{id}/individual-details', 'Partner\PartnerIndividualController@registerIndividual');
    Route::get('/{id}/individual-details', 'Partner\PartnerIndividualController@getIndividual');
    Route::post('/{id}/company-details', 'Partner\PartnerCompanyController@registerCompany');
    Route::get('/{id}/company-details', 'Partner\PartnerCompanyController@getCompany');

    Route::post('/{id}/services', 'Partner\PartnerServiceController@submitServices');
    Route::get('/{id}/services', 'Partner\PartnerServiceController@getServiceList');
    Route::post('/{id}/profile', 'Partner\PartnerProfileController@store');
    Route::get('/{id}/profile', 'Partner\PartnerProfileController@profileDetails');
    Route::post('/{id}/pricing', 'Partner\PartnerPricingController@updatePricing');
    Route::get('/{id}/pricing', 'Partner\PartnerPricingController@getPricing');
    Route::post('/{id}/logout', 'Partner\PartnerLoginController@logout');

    Route::get('/{id}/orders', 'Partner\PartnerOrderController@getOrders');

    Route::post('/{id}/add-service', 'Partner\PartnerServiceController@addService');

    Route::get('/{id}/mobile-verified-status', 'Partner\PartnerRegisterController@getMobileVerifiedStatus');
});


/**
 * Admin routes that do not require authentication
 */
Route::prefix('admins')->group(function() {
    Route::post('/login', 'Admin\AdminLoginController@login');
});

/**
 * Admin routes that require authentication
 */
Route::group(['prefix' => 'admins', 'middleware' => ['admin.token']], function()  {
    Route::post('/{id}/logout', 'Admin\AdminLoginController@logout');
    Route::get('/{id}/partners', 'Admin\AdminPartnerController@partners');
    Route::get('/{id}/customers', 'Admin\AdminCustomerController@getAllCustomers');
    Route::get('/{id}/orders', 'Admin\AdminOrderController@getOrders');
    Route::post('/{id}/orders/{order_id}/status', 'Admin\AdminOrderController@changeStatus');
    Route::post('/{id}/orders/{order_id}/reassign', 'Admin\AdminOrderController@reassign');
    Route::get('/{id}/partners/{partner_id}/services', 'Admin\AdminPartnerController@partnerDetails');    
});

Route::prefix('customers')->group(function() {
    Route::post('/login', 'Customer\CustomerLoginController@login');
    Route::post('/send-otp', 'Customer\CustomerOtpController@sendOtp');
    Route::post('/check-mobile-availability', 'Customer\CustomerOtpController@checkMobileAvailability');
    Route::post('/{id}/verify-otp', 'Customer\CustomerOtpController@verifyOtp');
    Route::post('/register', 'Customer\CustomerLoginController@register');
    Route::get('/categories', 'Customer\CustomerOrderController@allCategories');
    Route::get('/services', 'Customer\CustomerOrderController@allServices');
    Route::get('/categories/{id}/partners', 'Customer\CustomerOrderController@getPartnersByCategory');
    Route::get('/services/{id}/partners', 'Customer\CustomerOrderController@getPartnersByService');
    Route::get('/partners/{id}/services', 'Customer\CustomerOrderController@getPartnerServices');
});

/**
 * Customer routes that need authentication
 */
Route::group(['prefix' => 'customers', 'middleware' => ['customer.token', 'customer.active']], function()  {
    Route::get('/{id}/partner-ratings', 'Customer\CustomerRatingController@getAllPartnerRatings');
    Route::post('/{id}/partner/{partner_id}/ratings', 'Customer\CustomerRatingController@ratePartner');
    Route::post('/{id}/logout', 'Customer\CustomerLoginController@logout'); 
    Route::post('/{id}/update-location', 'Customer\CustomerProfileController@updateLocation'); 
});


Route::get('/services', 'ServiceController@index');
Route::get('/time-zones', 'TimeZoneController@index');
Route::get('/countries', 'CountryController@index');
Route::get('/tax-rates', 'CommonController@getTaxRates');