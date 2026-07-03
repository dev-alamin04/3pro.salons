<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Goals\GoalController;
use App\Http\Controllers\Api\Salons\BadgeController;
use App\Http\Controllers\Api\Salons\DailyTaskController;
use App\Http\Controllers\Api\Salons\SalonController;
use App\Http\Controllers\Api\Salons\TeamManagementController;
use Illuminate\Support\Facades\Route;

// API v1 routes --------------------------------------------------------------------------

// User Register ----------------------------------------------------------------------
Route::middleware('throttle:5,1')->controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('resend-otp', 'resendOtp');
    Route::post('verify-otp', 'verifyRegisterOtp');
});

// User Login -------------------------------------------------------------------------
Route::middleware('throttle:5,1')->controller(LoginController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('verify-reset-otp', 'verifyOtp');
    Route::post('reset-resend-otp', 'resendOtp');
    Route::post('reset-password', 'resetPassword');
    Route::post('set-password', 'setPassword');
});

// Protected routes -------------------------------------------------------------------
Route::middleware(['auth:sanctum', 'enabled'])->group(function () {
    // Authenticated User -------------------------------------------------------------
    Route::controller(AuthController::class)->group(function () {
        Route::apiResource('users', AuthController::class)->only(['index', 'show']);
        Route::post('change-password', 'changePassword');
        Route::put('update-profile', 'updateProfile');
        Route::get('profile', 'profile');
        Route::post('logout', 'logout');
        Route::post('logout-all', 'logoutAll');
        Route::delete('delete-account', 'destroy');
    });

    Route::get('get-onboardings', [SalonController::class, 'index']);
    Route::get('onboardings', [SalonController::class, 'getSalonOnboardings']);
    Route::post('set-onboardings', [SalonController::class, 'syncOnboardings']);
    Route::delete('remove-onboardings/{salonOnboarding}', [SalonController::class, 'removeOnboarding']);

    Route::post('create-account', [TeamManagementController::class, 'createAccount']);
    Route::get('my-team', [TeamManagementController::class, 'myTeams']);
    Route::post('team-switch/{user}', [TeamManagementController::class, 'teamswitch']);
    Route::post('/find-by-secret', [TeamManagementController::class, 'findbySecretKey']);
    Route::get('/history/{user}', [TeamManagementController::class, 'ProfileHistory']);
    Route::get('/dashboard', [TeamManagementController::class, 'dashboard']);

    Route::post('goal-create', [GoalController::class, 'store']);
    Route::get('last-goal/{user}', [GoalController::class, 'lastGaol']);
    Route::get('my-goals', [GoalController::class, 'myGoals']);
    Route::post('update-goal/level/{goal}', [GoalController::class, 'updateLevel']);

    Route::get('piller-details/{user}', [GoalController::class, 'pillerDetails']);

    Route::get('badges-history/{user}', [BadgeController::class, 'badgesHistory']);

    Route::get('badges', [BadgeController::class, 'index']);
    Route::get('badges/{badge}', [BadgeController::class, 'show']);
    Route::post('badges', [BadgeController::class, 'storeBadge']);
    Route::put('badges/{badge}', [BadgeController::class, 'updateBadge']);
    Route::delete('badges/{badge}', [BadgeController::class, 'destroy']);
    Route::get('/pillar-details/{pillar}', [BadgeController::class, 'pillarDetails']);
    Route::post('/badge-reponsed/{badge}', [BadgeController::class, 'acceptReject']);

    Route::post('daily-task', [DailyTaskController::class, 'store']);
    Route::put('daily-task/{dailyTask}', [DailyTaskController::class, 'update']);
    Route::delete('daily-task/{dailyTask}', [DailyTaskController::class, 'destroy']);
    Route::post('daily-task/{dailyTask}/mark-as-completed', [DailyTaskController::class, 'markasCompleted']);

});
