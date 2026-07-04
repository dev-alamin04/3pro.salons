<?php

use App\Http\Controllers\Web\Backend\Badge\BadgeController;
use App\Http\Controllers\Web\Backend\Dashboard\DashboardController;
use App\Http\Controllers\Web\Backend\Goal\GoalController;
use App\Http\Controllers\Web\Backend\Onboarding\OnboardingController;
use App\Http\Controllers\Web\Backend\Report\ReportController;
use App\Http\Controllers\Web\Backend\Salon\SalonController;
use App\Http\Controllers\Web\Backend\Task\DailyTaskController;
use App\Http\Controllers\Web\Backend\Team\TeamController;
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
Route::resource('onboardings', OnboardingController::class)->except(['create', 'show']);
Route::patch('onboardings/{onboarding}/status', [OnboardingController::class, 'updateStatus'])->name('onboardings.status');

// Goals Controller _________________________________________________________________
Route::get('goals/{userId}/user-goals', [GoalController::class, 'userGoals'])->name('admin.goals.user');
Route::resource('goals', GoalController::class)->only(['index', 'show'])->names('admin.goals');

// Reports Controller _________________________________________________________________
Route::resource('reports', ReportController::class)->only(['index', 'show'])->names('admin.reports');

// Badges Controller _________________________________________________________________
Route::resource('badges', BadgeController::class)->only(['index', 'show'])->names('admin.badges');

// Team Management Controller _________________________________________________________________
Route::resource('team', TeamController::class)->only(['index', 'show'])->names('admin.team');

// Daily Tasks Controller _________________________________________________________________
Route::resource('tasks', DailyTaskController::class)->only(['index', 'show'])->names('admin.tasks');
