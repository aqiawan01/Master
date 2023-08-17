<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController as AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function() {

	// Customer Auth Start Created By MYTECH MAESTRO
	Route::group(['prefix' => 'customer'], function() {

        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('resend-otp', [AuthController::class, 'resendOtp']);
        Route::get('forgot-password', [AuthController::class, 'forgetPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);

        Route::group(['prefix' => 'social'], function() 
        {
            Route::post('/google', [AuthController::class, 'googleSignIn']);
            Route::post('/facebook', [AuthController::class, 'facebookSignIn']);
            Route::post('/apple', [AuthController::class, 'appleSignIn']);
        });

        Route::group(['middleware' => 'auth:api'], function() 
        {
            Route::get('/getProfile', [AuthController::class, 'getProfile']);
            Route::post('change-password', [AuthController::class, 'changePassword']);
            Route::post('/update-profile', [AuthController::class, 'UpdateProfile']);
            Route::post('saveUserDeviceToken', [AuthController::class, 'saveUserDeviceToken']);
            Route::get('sign-out', [AuthController::class, 'signOut']);
        });

    });
    // Customer Auth End Created By MYTECH MAESTRO

});
