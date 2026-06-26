<?php

use App\Http\Controllers\Web\Backend\Dashboard\DashboardController;
use App\Http\Controllers\Web\Backend\Salon\SalonController;
use App\Http\Controllers\Web\Backend\User\UserController;
use Illuminate\Support\Facades\Route;

//  Users Controller _________________________________________________________________
Route::resource('users', UserController::class);
Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
Route::patch('users/{user}/account-status', [UserController::class, 'updateAccountStatus'])->name('users.account-status');

// Dashboard Controller _______________________________________________________
Route::controller(DashboardController::class)->prefix('/dashboard')->group(function () {
    Route::get('/', 'index')->name('admin.dashboard');
    Route::get('/metrics', 'metrics')->name('admin.dashboard.metrics');
    Route::get('/transaction-history', 'transactionHistory');
    Route::get('/sales-chart', 'salesChart');
});

Route::resource('salons', SalonController::class);
Route::get('salons/{salon}/assign', [SalonController::class, 'assignedUsers'])->name('salons.assign');
Route::post('salons/{salon}/assign', [SalonController::class, 'assignUser'])->name('salons.assign.user');
Route::delete('salons/{salon}/users/{user}', [SalonController::class, 'removeUser'])->name('salons.remove.user');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
