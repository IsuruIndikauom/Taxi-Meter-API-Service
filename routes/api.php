<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TariffController;
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


Route::resource('otps', OTPController::class);
Route::post('otps/verify',[OTPController::class, 'verify']);
Route::middleware('auth:api')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('tariffs',TariffController::class);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('trips/start',[TripController::class, 'start']);
    Route::post('trips/inprogress/{trip}',[TripController::class, 'inProgress']);
    Route::post('trips/end/{trip}',[TripController::class, 'end']);
});