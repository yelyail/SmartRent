<?php

use App\Http\Controllers\Properties;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    LandingController,
    LandlordController,
    TenantsController,
    DashboardController,
    AdminController,
    StaffController,
    UserManagementController,
};
use Illuminate\Support\Facades\Log;
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

        // AJAX routes for user management
        Route::get('/users/stats', [AdminController::class, 'getUserStats'])->name('admin.users.stats');
        Route::get('/kyc/pending', [AdminController::class, 'getPendingKyc'])->name('admin.kyc.pending');

        // User Management Routes
        Route::prefix('users')->name('admin.users.')->group(function () {
            Route::get('/', [UserManagementController::class, 'getUsers'])->name('getUsers');
            Route::post('/', [UserManagementController::class, 'store'])->name('store');
            Route::get('/{user}', [UserManagementController::class, 'getUser'])->name('getUser');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
            Route::patch('/{user}/status', [UserManagementController::class, 'updateStatus'])->name('updateStatus');
            Route::patch('/{user}/archive', [UserManagementController::class, 'archive'])->name('archive');
            Route::get('/{user}/kyc', [UserManagementController::class, 'getKycDetails'])->name('getKycDocuments');
        });

        // KYC Routes
        Route::prefix('kyc')->name('admin.kyc.')->group(function () {
            Route::get('/pending', [UserManagementController::class, 'getPendingKyc'])->name('pending');
            Route::post('/{kycDocument}/approve', [UserManagementController::class, 'approveKyc'])->name('approve');
            Route::post('/{kycDocument}/reject', [UserManagementController::class, 'rejectKyc'])->name('reject');
        });

       Route::get('/admin/pdf/{filename}', function($filename) {
            $decodedFilename = urldecode($filename);
            
            // Check if file exists
            $path = public_path("storage/images/uploadDocs/{$decodedFilename}");
            
            if (!file_exists($path)) {
                // Get available files to suggest alternatives
                $directory = public_path('storage/images/uploadDocs');
                $availableFiles = [];
                if (is_dir($directory)) {
                    $files = scandir($directory);
                    $availableFiles = array_values(array_filter($files, function($file) {
                        return $file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'pdf';
                    }));
                }
                
                // Check if this might be a different file with similar name
                $suggestedFiles = [];
                foreach ($availableFiles as $file) {
                    if (strpos($file, 'kyc_doc') !== false) {
                        $suggestedFiles[] = $file;
                    }
                }
                
                return response()->json([
                    'error' => 'File not found',
                    'requested_file' => $decodedFilename,
                    'available_files' => $availableFiles,
                    'suggested_kyc_files' => $suggestedFiles,
                ], 404);
            }
            
            // Check if file is PDF
            $fileExtension = pathinfo($path, PATHINFO_EXTENSION);
            if (strtolower($fileExtension) !== 'pdf') {
                abort(403, 'Access denied for this file type.');
            }
            
            // Return the PDF file
            return response()->file($path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline',
            ]);
        })->name('files.pdf')->middleware(['auth', 'role:admin']);
    });
});


//for the landlord
Route::middleware(['auth', 'role:landlord'])->prefix('landlord')->name('landlords.')->group(function () {
    // Dashboard & Main Pages
    Route::get('/dashboard', [LandlordController::class, 'index'])->name('dashboard');
    Route::get('/payment', [LandlordController::class, 'payment'])->name('payment');
    Route::get('/prop-assets', [LandlordController::class, 'propAssets'])->name('propAssets');
    Route::get('/analytics', [LandlordController::class, 'analytics'])->name('analytics');
    Route::get('/maintenance', [LandlordController::class, 'maintenance'])->name('maintenance');
    Route::get('/bills', [LandlordController::class, 'bills'])->name('bills');
    Route::get('/maintenance-requests', [LandlordController::class, 'maintenanceRequests'])->name('maintenanceRequests');
    Route::get('/user-management', [LandlordController::class, 'userManagement'])->name('userManagement');
    
    //tenants
    // Add these routes
    Route::post('/leases/{id}/approve', [LandlordController::class, 'approveLease'])->name('leases.approve');
    Route::post('/leases/{id}/terminate', [LandlordController::class, 'terminateLease'])->name('leases.terminate');
    
    // Properties routes
    Route::get('/properties', [LandlordController::class, 'properties'])->name('properties');
    Route::get('/tenants/{id}/details', [LandlordController::class, 'getTenantDetails'])->name('tenants.details');
    
    // Add KYC status update route
    Route::post('/kyc/{id}/status', [LandlordController::class, 'updateKycStatus'])->name('kyc.update-status');
    
    Route::post('/properties', [Properties::class, 'store'])->name('properties.store');
    Route::get('/properties/{id}', [Properties::class, 'show'])->name('properties.show');
    Route::get('/properties/{id}/edit', [Properties::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{id}', [Properties::class, 'update'])->name('properties.update');
    
    // Property Units routes
    Route::post('/properties/{id}/units', [Properties::class, 'storeUnit'])->name('properties.units.store');
    Route::get('/properties/{id}/units', [Properties::class, 'getUnits'])->name('properties.units.index');
    Route::get('/units/{id}/edit', [Properties::class, 'editUnit'])->name('units.edit');
    Route::put('/units/{id}', [Properties::class, 'updateUnit'])->name('units.update');
    Route::put('/units/{id}/archive', [Properties::class, 'archiveUnit'])->name('units.archive');
    
    // Smart Devices routes
    Route::post('/properties/{id}/devices', [Properties::class, 'storeDevice'])->name('properties.devices.store');
    Route::get('/properties/{id}/devices', [Properties::class, 'getSmartDevices'])->name('properties.devices.index');
    Route::get('/devices/{id}/edit', [Properties::class, 'editDevice'])->name('devices.edit');
    Route::put('/devices/{id}', [Properties::class, 'updateDevice'])->name('devices.update');
    Route::delete('/devices/{id}', [Properties::class, 'destroyDevice'])->name('devices.destroy');
    Route::put('/devices/{id}/archive', [Properties::class, 'archiveDevice'])->name('devices.archive');
    
    // Device Status Update (for AJAX power control)
    Route::post('/devices/{id}/status', [LandlordController::class, 'updateDeviceStatus'])->name('devices.update-status');
});

//for the tenants
Route::middleware(['auth', 'role:tenants'])->prefix('tenants')->name('tenants.')->group(function () {
    Route::get('/dashboard', [TenantsController::class, 'index'])->name('dashboard');
    Route::get('/payment', [TenantsController::class, 'payment'])->name('payment');
    Route::get('/prop-assets', [TenantsController::class, 'propAssets'])->name('propAssets');
    Route::post('/smart-devices/{id}/toggle', [TenantsController::class, 'toggleDevice'])->name('smartDevices.toggle'); // Fixed name
 
    Route::get('/analytics', [TenantsController::class, 'analytics'])->name('analytics');
    Route::get('/maintenance', [TenantsController::class, 'maintenance'])->name('maintenance'); 
    Route::get('/properties', [TenantsController::class, 'properties'])->name('properties');    
    Route::get('/user-management', [TenantsController::class, 'userManagement'])->name('userManagement');
    Route::post('/leases/{id}/pay-deposit', [TenantsController::class, 'payDeposit'])->name('leases.payDeposit');    
    Route::get('/submit-maintenance-request', [TenantsController::class, 'submitMaintenanceRequest'])->name('submitMaintenanceRequest');
    Route::get('/payment-history', [TenantsController::class, 'paymentHistory'])->name('paymentHistory');

    Route::get('/properties/{id}', [TenantsController::class, 'getProperty'])->name('properties.details');
    Route::post('/properties/{id}/rent', [TenantsController::class, 'rentProperty'])->name('properties.rent');
    
    // FIXED: Renamed to avoid conflict
    Route::get('/my-leases', [TenantsController::class, 'myLeases'])->name('myLeases'); // Changed from 'renting'
    Route::get('/leases', [TenantsController::class, 'leases'])->name('leases');
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



Route::get('/debug-database-kyc', function() {
    $kycDocuments = \App\Models\KycDocument::all();
    
    return response()->json(
        $kycDocuments->map(function($doc) {
            return [
                'kyc_id' => $doc->kyc_id,
                'user_id' => $doc->user_id,
                'doc_name' => $doc->doc_name,
                'doc_path' => $doc->doc_path,
                'proof_of_income' => $doc->proof_of_income,
                'file_exists' => file_exists(public_path("storage/{$doc->doc_path}")),
                'income_file_exists' => $doc->proof_of_income ? file_exists(public_path("storage/{$doc->proof_of_income}")) : false,
            ];
        })
    );
})->middleware(['auth', 'role:admin']);