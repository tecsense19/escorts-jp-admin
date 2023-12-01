<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HomeController;

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
    Route::get('/escorts/list', [HomeController::class, 'escortsList']);
    Route::post('/escorts/date/wise/availability', [HomeController::class, 'getDateWiseAvailability']);
    Route::post('/escorts/booking', [HomeController::class, 'escortsBooking']);
});
