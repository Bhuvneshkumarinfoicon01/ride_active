<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RideController;
use App\Http\Controllers\Api\DriverController;

use App\Http\Controllers\Api\AuthController;

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes([
    'middleware' => ['auth:sanctum'],
]);

Route::post('/driver/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post(
    '/driver/logout',
    [AuthController::class, 'logout']
);

Route::post('/assign-ride', [RideController::class, 'assignRide']);

Route::middleware('auth:sanctum')->prefix('driver')->group(function () {

    Route::get('/rides', [DriverController::class, 'rides']);

    Route::get('/rides/{id}', [DriverController::class, 'rideDetails']);

    Route::post('/rides/{id}/status', [DriverController::class, 'updateRideStatus']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});


