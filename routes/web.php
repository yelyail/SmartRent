<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    LandingController,
    LandlordController,
    TenantsController,
    DashboardController,
    AdminController,
    StaffController
};
use App\Http\Controllers\Auth\AuthController;

Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/about', [LandingController::class, 'about'])->name('landing.about');
Route::get('/pricing', [LandingController::class, 'pricing'])->name('landing.pricing');
Route::get('/contact', [LandingController::class, 'contact'])->name('landing.contact');
   
// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'storeLogin'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'storeRegister'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//for the admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admins.dashboard');
        Route::get('/bill', [AdminController::class, 'bill'])->name('admins.bill');
        Route::get('/analytics', [AdminController::class, 'analytics'])->name('admins.analytics');
        Route::get('/maintenance', [AdminController::class, 'maintenance'])->name('admins.maintenance');
        Route::get('/prop-assets', [AdminController::class, 'propAssets'])->name('admins.propAssets');
        Route::get('/user-management', [AdminController::class, 'userManagement'])->name('admins.userManagement');
        Route::get('/properties', [AdminController::class, 'properties'])->name('admins.properties');
        Route::get('/payment', [AdminController::class, 'payment'])->name('admins.payment');
    });
});

//for the landlord
Route::middleware(['auth', 'role:landlord'])->group(function () {
    Route::get('/dashboard', [LandlordController::class, 'index'])->name('landlords.dashboard');
    Route::get('/properties', [LandlordController::class, 'properties'])->name('landlords.properties');
    Route::get('/tenants', [LandlordController::class, 'tenants'])->name('landlords.tenants');
    Route::get('/bills', [LandlordController::class, 'bills'])->name('landlords.bills');
    Route::get('/maintenance-requests', [LandlordController::class, 'maintenanceRequests'])->name('landlords.maintenanceRequests');    
}); 

//for the tenants
Route::middleware(['auth', 'role:tenant'])->group(function () {
    Route::get('/dashboard', [TenantsController::class, 'index'])->name('tenants.dashboard');
    Route::get('/my-bills', [TenantsController::class, 'myBills'])->name('tenants.myBills');
    Route::get('/submit-maintenance-request', [TenantsController::class, 'submitMaintenanceRequest'])->name('tenants.submitMaintenanceRequest');
    Route::get('/payment-history', [TenantsController::class, 'paymentHistory'])->name('tenants.paymentHistory');
}); 

//for the staff
Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    Route::get('/bill', [StaffController::class, 'bill'])->name('staff.bill');
    Route::get('/analytics', [StaffController::class, 'analytics'])->name('staff.analytics');
    Route::get('/maintenance', [StaffController::class, 'maintenance'])->name('staff.maintenance');
    Route::get('/prop-assets', [StaffController::class, 'propAssets'])->name('staff.propAssets');
    Route::get('/user-management', [StaffController::class, 'userManagement'])->name('staff.userManagement');
    Route::get('/properties', [StaffController::class, 'properties'])->name('staff.properties');
    Route::get('/payment', [StaffController::class, 'payment'])->name('staff.payment');
});