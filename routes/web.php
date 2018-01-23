<?php

Route::prefix('payments/customers')->group(function() {
    Route::get(
        'create-order', 
        'Customer\CustomerPaymentController@initiatePayment')
        ->name('customer-create-order');
    Route::post(
        'payment-success', 
        'Customer\CustomerPaymentController@successfulPayment')
        ->name('customer-payment-success');
    Route::post(
        'payment-failed', 
        'Customer\CustomerPaymentController@failedPayment')
        ->name('customer-payment-failure');;
});