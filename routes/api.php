<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\EscortsController;
use App\Http\Controllers\Api\V1\SettingController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/v1/sent/otp', [HomeController::class, 'sentOtp']);
Route::post('/v1/verify/otp', [HomeController::class, 'verifyOtp']);

Route::group(['prefix' => 'v1'], function () {
    Route::post('/escorts/list', [HomeController::class, 'escortsList']);
    Route::post('/country/list', [HomeController::class, 'countryList']);
    Route::post('/state/list', [HomeController::class, 'stateList']);
    Route::post('/city/list', [HomeController::class, 'cityList']);
    Route::post('/escorts/date/wise/availability', [HomeController::class, 'getDateWiseAvailability']);
    Route::post('/escorts/booking', [HomeController::class, 'escortsBooking']);
    Route::post('/booking/list', [HomeController::class, 'bookingList']);

    Route::post('/register', [EscortsController::class, 'register']);
    Route::post('/escort/login', [EscortsController::class, 'login']);
    Route::post('/escort/profile', [EscortsController::class, 'getEscortProfile']);
    Route::post('/escort/availability/add', [EscortsController::class, 'escortAvailabilityAdd']);
    Route::post('/escort/change/password', [EscortsController::class, 'changePassword']);
    Route::post('/escort/forgot/password', [EscortsController::class, 'forgotPassword']);

    Route::post('/favourite/escort', [EscortsController::class, 'favouriteEscorts']);

    Route::post('/get/all/string', [HomeController::class, 'getAllString']);

    Route::post('/terms-privacypolicy/list', [HomeController::class, 'terms_privacypolicy']);

    Route::post('/user/update/profile', [HomeController::class, 'updateProfile']);
});
