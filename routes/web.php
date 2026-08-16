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
use App\Http\Controllers\AdminMealReportController;
use App\Http\Controllers\AdminMealDashboardController;
use App\Http\Controllers\AdminMealController;
use App\Http\Controllers\AdminMealPlanController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\AdminSubscriptionController;
use App\Http\Controllers\RoleDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\MealCustomizationController;

Route::get('/', [HomeController::class, 'index'])
    ->name('landing');

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



    // Meal customization  

    Route::get(
        '/plans/{mealPlan}/customize',
        [MealCustomizationController::class, 'create']
    )->name('meal-plans.customize');

    Route::post(
        '/plans/{mealPlan}/customize',
        [MealCustomizationController::class, 'store']
    )->name('meal-plans.customize.store');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {


    // Role-aware entry point
    Route::get('/home', [RoleDashboardController::class, 'index'])
        ->name('home');


    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])
        ->name('staff.dashboard');

    // Existing customer dashboard

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



Route::middleware('auth')->group(function () {

    Route::get(
        '/admin/meals/report',
        [AdminMealReportController::class, 'index']
    )->name('admin.meals.report');

    Route::get(
        '/admin/meals/report/export',
        [AdminMealReportController::class, 'export']
    )->name('admin.meals.report.export');


    Route::get(
        '/admin/meals/dashboard',
        [AdminMealDashboardController::class, 'index']
    )->name('admin.meals.dashboard');





        // Meal administration
    Route::prefix('admin')->group(function () {


      /*
    |--------------------------------------------------------------------------
    | MEAL ADMINISTRATION
    |--------------------------------------------------------------------------
    */
            Route::resource(
                'meal-plans',
                AdminMealPlanController::class
            )->names('admin.meal-plans');

            Route::post(
                'meal-plans/{mealPlan}/toggle',
                [AdminMealPlanController::class, 'toggle']
            )->name('admin.meal-plans.toggle');


            Route::resource(
                'meals',
                AdminMealController::class
            )->names('admin.meals');

            Route::post(
                'meals/{meal}/toggle',
                [AdminMealController::class, 'toggle']
            )->name('admin.meals.toggle');



             /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    */

            Route::resource(
                'users',
                AdminUserController::class
            )->names('admin.users');

            Route::post(
                'users/{user}/toggle',
                [AdminUserController::class, 'toggle']
            )->name('admin.users.toggle');

            Route::post(
                'users/{user}/reset-password',
                [AdminUserController::class, 'resetPassword']
            )->name('admin.users.reset-password');



             /*
    |--------------------------------------------------------------------------
    | Payment administration
    |--------------------------------------------------------------------------
    */

            Route::get(
                '/payments',
                [AdminPaymentController::class, 'index']
            )->name('admin.payments.index');

            Route::get(
                '/payments/{payment}',
                [AdminPaymentController::class, 'show']
            )->name('admin.payments.show');

            Route::post(
                '/payments/{payment}/verify',
                [AdminPaymentController::class, 'verify']
            )->name('admin.payments.verify');



            /*
|--------------------------------------------------------------------------
| SUBSCRIPTION ADMINISTRATION
|--------------------------------------------------------------------------
*/

            Route::get(
                'subscriptions',
                [AdminSubscriptionController::class, 'index']
            )->name('admin.subscriptions.index');

            Route::get(
                'subscriptions/{subscription}',
                [AdminSubscriptionController::class, 'show']
            )->name('admin.subscriptions.show');

            Route::post(
                'subscriptions/{subscription}/cancel',
                [AdminSubscriptionController::class, 'cancel']
            )->name('admin.subscriptions.cancel');

            Route::post(
                'subscriptions/{subscription}/reactivate',
                [AdminSubscriptionController::class, 'reactivate']
            )->name('admin.subscriptions.reactivate');



                    /*
        |--------------------------------------------------------------------------
        ADMIN DASHBOARD
        |--------------------------------------------------------------------------
        */

            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');



    });




});