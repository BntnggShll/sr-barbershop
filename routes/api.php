<?php
use App\Http\Controllers\API\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\LoginGoogleController;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\FinancialController;
use App\Http\Controllers\API\LoyaltyController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductSaleController;
use App\Http\Controllers\API\ReservationsController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\scheduleController;
use App\Http\Controllers\API\WorkDocumentationController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RegisterPekerjaController;



    Route::post('/login', [LoginController::class, 'store']);
    Route::post('/register', [RegisterController::class, 'register']);
Route::post('/registerpekerja',[RegisterPekerjaController::class,'registerpekerja']);

Route::get('auth/google', [LoginGoogleController::class, 'redirectToGoogle'])
    ->middleware(EnsureTokenIsValid::class);

Route::get('auth/google/callback', [LoginGoogleController::class, 'handleGoogleCallback']);
// subscriptions
Route::get('/subscriptions', [SubscriptionController::class, 'index']);
Route::get('/subscriptions/{id}', [SubscriptionController::class, 'show']);
Route::post('/subscriptions', [SubscriptionController::class, 'store']);
Route::put('/subscriptions/{id}', [SubscriptionController::class, 'update']);
Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy']);
// financial-reports
Route::get('/financial-reports', [FinancialController::class, 'index']);
Route::get('/financial-reports/{id}', [FinancialController::class, 'show']);
Route::post('/financial-reports', [FinancialController::class, 'store']);
Route::put('/financial-reports/{id}', [FinancialController::class, 'update']);
Route::delete('/financial-reports/{id}', [FinancialController::class, 'destroy']);
// loyalty
Route::get('/loyalty', [LoyaltyController::class, 'index']);
Route::get('/loyalty/{id}', [LoyaltyController::class, 'show']);
Route::post('/loyalty', [LoyaltyController::class, 'store']);
Route::put('/loyalty/{id}', [LoyaltyController::class, 'update']);
Route::delete('/loyalty/{id}', [LoyaltyController::class, 'destroy']);
// payments
Route::get('/payments', [PaymentController::class, 'index']);
Route::get('/payments/{id}', [PaymentController::class, 'show']);
Route::post('/payments', [PaymentController::class, 'store']);
Route::put('/payments/{id}', [PaymentController::class, 'update']);
Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);
// products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
// product-sales
Route::get('/product-sales', [ProductSaleController::class, 'index']);
Route::get('/product-sales/{id}', [ProductSaleController::class, 'show']);
Route::post('/product-sales', [ProductSaleController::class, 'store']);
Route::put('/product-sales/{id}', [ProductSaleController::class, 'update']);
Route::delete('/product-sales/{id}', [ProductSaleController::class, 'destroy']);
// reservations
Route::get('/reservations', [ReservationsController::class, 'index']);
Route::get('/reservations/{id}', [ReservationsController::class, 'show']);
Route::post('/reservations', [ReservationsController::class, 'store']);
Route::put('/reservations/{id}', [ReservationsController::class, 'update']);
Route::delete('/reservations/{id}', [ReservationsController::class, 'destroy']);
// reviews
Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/{id}', [ReviewController::class, 'show']);
Route::post('/reviews', [ReviewController::class, 'store']);
Route::put('/reviews/{id}', [ReviewController::class, 'update']);
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
// schedules
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/{id}', [ScheduleController::class, 'show']);
Route::post('/schedules', [ScheduleController::class, 'store']);
Route::put('/schedules/{id}', [ScheduleController::class, 'update']);
Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);
// services
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::post('/services', [ServiceController::class, 'store']);
Route::put('/services/{id}', [ServiceController::class, 'update']);
Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
// work-documentation
Route::apiResource('work-documentation', WorkDocumentationController::class);
// User
Route::get('/admin', [UserController::class, 'admin']);
Route::get('/user', [UserController::class, 'user']);
Route::get('/pekerja', [UserController::class, 'pekerja']);
Route::delete('/user/{id}', [UserController::class, 'destroy']);











