<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServicesController;

Route::post('auth/login', [AuthController::class, 'login']);

// Route::post('requestRecovery', 'AuthController@requestRecovery');
// Route::get('verifyRecoveryKey/{recovery_key}', 'AuthController@verifyRecoveryKey');
// Route::post('resetPassword', 'AuthController@resetPassword');

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('auth/checkLoginStatus', [AuthController::class, 'checkLoginStatus']);
    Route::get('auth/logout', [AuthController::class, 'logout']);

    Route::get('services/get', [ServicesController::class, 'get']);
    Route::resource('services', ServicesController::class)->except(['create', 'show', 'edit']);
});