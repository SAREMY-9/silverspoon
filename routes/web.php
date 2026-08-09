<?php

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MpesaCallbackController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::post('/subscriptions', [
        SubscriptionController::class,
        'store',
    ])->name('subscriptions.store');
});


Route::middleware('auth')->group(function () {

    Route::post(
        '/checkout/{mealPlan}',
        [CheckoutController::class, 'initiate']
    )->name('checkout.initiate');

});


Route::post(
    '/api/mpesa/callback',
    [MpesaCallbackController::class, 'handle']
)->name('mpesa.callback');

