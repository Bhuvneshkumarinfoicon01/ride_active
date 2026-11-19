<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DriverController;


// Login

Route::get('/', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');


// Logout

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// Admin routes

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/assign-ride', [AdminController::class, 'assignRide'])
            ->name('assignRide');

    });


// Driver routes

Route::middleware(['auth', 'role:driver'])
    ->prefix('driver')
    ->name('driver.')
    ->group(function () {

        Route::get('/dashboard', [DriverController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/ride/{ride}/status', [
            DriverController::class,
            'updateRideStatus'
        ])->name('ride.status');

    });