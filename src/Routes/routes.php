<?php

use Illuminate\Support\Facades\Route;
use Webkul\ZiraatBank\Http\Controllers\ZiraatBankController;

Route::group(['middleware' => ['web'], 'prefix' => 'ziraat_bank/payment'], function () {
    Route::controller(ZiraatBankController::class)->group(function () {
        Route::get('/direct', 'redirect')->name('ziraat_bank.payment.redirect');

        Route::post('/transaction', 'transaction')->name('ziraat_bank.payment.transaction');
    });
});
