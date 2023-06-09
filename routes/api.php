<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
/**
 * User routes
 */
Route::group(['prefix' => 'users'], function () {
    Route::post('login', [\App\Http\Controllers\Api\AuthenticateController::class, 'login'])->name('login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('info', [\App\Http\Controllers\Api\AuthenticateController::class, 'info']);
    });
});

Route::group(['prefix' => 'loans'], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\LoanApplicationController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\Api\LoanApplicationController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\Api\LoanApplicationController::class, 'store']);
    });
});

Route::group(['prefix' => 'payments'], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\PaymentController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\Api\PaymentController::class, 'show']);
        Route::post('make_payment', [\App\Http\Controllers\Api\PaymentController::class, 'makePayment']);
    });
});

/**
 * Admin routes
 */
Route::group(['prefix' => 'admin'], function () {
    Route::post('login', [\App\Http\Controllers\Api\Admin\AuthenticateController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('info', [\App\Http\Controllers\Api\Admin\AuthenticateController::class, 'info']);
    });
    Route::group(['prefix' => 'loans'], function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Admin\LoanApplicationController::class, 'index']);
            Route::post('approve/{id}', [\App\Http\Controllers\Api\Admin\LoanApplicationController::class, 'approve']);
        });
    });
});
