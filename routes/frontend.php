<?php

use App\Http\Controllers\Web\Frontend\HomeController;
use App\Http\Controllers\Web\Frontend\PageController;
use App\Http\Controllers\Web\Frontend\ResetController;
use Illuminate\Support\Facades\Route;

//! Route for Reset Database and Optimize Clear_______________________________________________________
// Route::get('/reset', [ResetController::class, 'RunMigrations'])->name('reset');
Route::get('/composer', [ResetController::class, 'composer'])->name('composer');
Route::get('/migrate', [ResetController::class, 'migrate'])->name('migrate');
Route::get('/storage', [ResetController::class, 'storage'])->name('storage');

//! Route for Landing Page_______________________________________________________
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/email-template', [HomeController::class, 'emailTempalete']);

//! Dynamic Page_______________________________________________________
// Route::get('/page/privacy-and-policy', [PageController::class, 'privacyAndPolicy'])->name('dynamicPage.privacyAndPolicy');

Route::get('/{slug}', action: [PageController::class, 'dynamicPageforWeb']); // terms-conditions, privacy-policies
