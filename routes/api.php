<?php

use Illuminate\Http\Request;
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

Route::group(['prefix' => 'users'], function () {
    Route::post('login', [\App\Http\Controllers\Api\AuthenticateController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('info', [\App\Http\Controllers\Api\AuthenticateController::class, 'info']);
    });
});

Route::group(['prefix' => 'loans'], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\LoanApplicationController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\LoanApplicationController::class, 'store']);
//        Route::post('make_payment', [\App\Http\Controllers\Api\AuthenticateController::class, 'login']);
    });
});


Route::group(['prefix' => 'admin'], function () {
    Route::post('login', [\App\Http\Controllers\Api\Admin\AuthenticateController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('info', [\App\Http\Controllers\Api\Admin\AuthenticateController::class, 'info']);
    });
    Route::group(['prefix' => 'loans'], function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Admin\LoanApplicationController::class, 'index']);
            Route::post('approve', [\App\Http\Controllers\Api\Admin\LoanApplicationController::class, 'approve']);
        });
    });

});


