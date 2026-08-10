<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MpesaCallbackController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\MealRedemptionController;
use App\Http\Controllers\StaffMealController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/register', [
        RegisteredUserController::class,
        'create',
    ])->name('register');

    Route::post('/register', [
        RegisteredUserController::class,
        'store',
    ]);

    Route::get('/login', [
        AuthenticatedSessionController::class,
        'create',
    ])->name('login');

    Route::post('/login', [
        AuthenticatedSessionController::class,
        'store',
    ]);
});



Route::get('/plans', [
    MealPlanController::class,
    'index',
])->name('meal-plans.index');

Route::get('/plans/{mealPlan}', [
    MealPlanController::class,
    'show',
])->name('meal-plans.show');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [
        DashboardController::class,
        'index',
    ])->name('dashboard');

    Route::post('/logout', [
        AuthenticatedSessionController::class,
        'destroy',
    ])->name('logout');


    // Subscriptions

    Route::post('/subscriptions', [
        SubscriptionController::class,
        'store',
    ])->name('subscriptions.store');


    // Checkout

    Route::get(
        '/checkout/{mealPlan}',
        [CheckoutController::class, 'show']
    )->name('checkout.show');

    Route::post('/checkout/{mealPlan}', [
        CheckoutController::class,
        'initiate',
    ])->name('checkout.initiate');

    Route::post('/checkout/{mealPlan}/paystack', [
        PaystackController::class,
        'initiate',
    ])->name('checkout.paystack');


    // Meal redemption

    Route::post(
        '/dashboard/meals/{mealEntitlement}/redeem',
        [MealRedemptionController::class, 'store']
    )->name('dashboard.meals.redeem');
});


/*
|--------------------------------------------------------------------------
| Payment Callbacks
|--------------------------------------------------------------------------
*/

Route::post('/api/mpesa/callback', [
    MpesaCallbackController::class,
    'handle',
])->name('mpesa.callback');

Route::get('/paystack/callback', [
    PaystackController::class,
    'callback',
])->name('paystack.callback');




Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | STAFF MEAL SERVICE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/staff/meals/scan',
        [StaffMealController::class, 'scanner']
    )->name('staff.meals.scan');

    Route::post(
        '/staff/meals/validate',
        [StaffMealController::class, 'validateQr']
    )->name('staff.meals.validate');

    Route::post(
        '/staff/meals/{mealEntitlement}/serve',
        [StaffMealController::class, 'serve']
    )->name('staff.meals.serve');


    Route::get(
        '/staff/meals/summary',
        [StaffMealController::class, 'summary']
    )->name('staff.meals.summary');

    

});