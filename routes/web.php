<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    LandingController,
    LandlordController,
    TenantsController
};
use App\Http\Controllers\Auth\AuthController;

Route::get('/', [LandingController::class, 'homepage'])->name('landing.homepage');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('Auth.register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('Auth.login');
  
    Route::prefix('/')->group(function () {
        Route::get('about', [LandingController::class, 'about'])->name('landing.about');
        Route::get('pricing', [LandingController::class, 'pricing'])->name('landing.pricing');
        Route::get('contact', [LandingController::class, 'contact'])->name('landing.contact');
    });
   