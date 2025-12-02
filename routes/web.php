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
    PaymentController,
    StaffController,
    UserManagementController,
    ReportController,
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
        Route::get('/analytics', [AdminController::class, 'analytics'])->name('admins.analytics');
        Route::get('/user-management', [AdminController::class, 'userManagement'])->name('admins.userManagement');
        Route::get('/properties', [AdminController::class, 'properties'])->name('admins.properties');
        
        // REPORTS ROUTES - Add this section
        Route::prefix('reports')->name('admin.reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/export/pdf', [ReportController::class, 'exportPDF'])->name('export.pdf');
        });
        // END REPORTS ROUTES

        // maintenance
        Route::get('/maintenance', [AdminController::class, 'maintenance'])->name('admins.maintenance');
        Route::post('/maintenance', [AdminController::class, 'store'])->name('admins.maintenance.store');
        Route::post('/maintenance/{id}/approve', [AdminController::class, 'approveRequest'])->name('admins.maintenance.approve');
        Route::post('/maintenance/{id}/status', [AdminController::class, 'updateStatus'])->name('admins.maintenance.update-status');
        Route::get('/maintenance/{id}/details', [AdminController::class, 'getRequestDetails'])->name('admins.maintenance.details');
        Route::post('/maintenance/{id}/invoice', [AdminController::class, 'createInvoice'])->name('admins.maintenance.create-invoice');
        Route::post('/billing/{id}/payment', [AdminController::class, 'recordPayment'])->name('admins.billing.record-payment');

        // AJAX routes for user management
        Route::get('/users/stats', [AdminController::class, 'getUserStats'])->name('admin.users.stats');
        Route::get('/kyc/pending', [AdminController::class, 'getPendingKyc'])->name('admin.kyc.pending');
        
        Route::get('/properties/{id}', [AdminController::class, 'getProperty'])->name('properties.details');

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
    Route::get('/maintenance-requests/{id}/details', [LandlordController::class, 'getRequestDetails'])->name('maintenance-requests.details');

    //tenants
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
    Route::get('/prop-assets', [TenantsController::class, 'propAssets'])->name('propAssets'); // smart devices
    Route::post('/smart-devices/{id}/toggle', [TenantsController::class, 'toggleDevice'])->name('smartDevices.toggle'); // smart device toggle
 
    Route::get('/analytics', [TenantsController::class, 'analytics'])->name('analytics'); // reports page

    Route::get('/maintenance', [TenantsController::class, 'maintenance'])->name('maintenance');
    Route::post('/maintenance/payment', [TenantsController::class, 'makePayment'])->name('maintenance.payment');
    Route::get('/maintenance/{id}/details', [TenantsController::class, 'getRequestDetails'])->name('maintenance.details');
    Route::post('/maintenance-requests', [TenantsController::class, 'store'])->name('maintenance-requests.store'); // Submit maintenance request

    Route::get('/properties', [TenantsController::class, 'properties'])->name('properties');    
    Route::get('/user-management', [TenantsController::class, 'userManagement'])->name('userManagement');

    Route::post('/leases/{leaseId}/pay-deposit', [PaymentController::class, 'payDeposit'])->name('leases.pay-deposit');
    Route::post('/leases/{leaseId}/pay-rent', [PaymentController::class, 'payRent'])->name('leases.pay-rent');


    Route::get('/payment-history', [TenantsController::class, 'paymentHistory'])->name('paymentHistory');

    Route::get('/properties/{id}', [TenantsController::class, 'getProperty'])->name('properties.details');
    Route::post('/properties/{id}/rent', [TenantsController::class, 'rentProperty'])->name('properties.rent');
    
    Route::get('/my-leases', [TenantsController::class, 'myLeases'])->name('myLeases');
    Route::get('/leases', [TenantsController::class, 'leases'])->name('leases');

    Route::get('/payments/export-report-pdf', [ReportController::class, 'exportPaymentHistoryPDF'])
        ->name('tenant.payments.export.pdf');

    // Add these API routes inside the tenants group
    Route::get('/api/payments/history', [PaymentController::class, 'getPaymentHistory'])->name('payments.history');
    Route::get('/api/billings', [PaymentController::class, 'getUserBillings'])->name('billings');
    Route::get('/api/payments/{paymentId}/invoice-pdf', [PaymentController::class, 'exportInvoicePdf'])->name('payments.invoice-pdf');
    Route::get('/api/payments/{paymentId}/invoice-details', [PaymentController::class, 'getInvoiceDetails'])->name('payments.invoice-details');
    Route::get('/api/payments/export-report-pdf', [PaymentController::class, 'exportPaymentReportPdf'])->name('payments.export-report');
    Route::get('/api/staff-members', [TenantsController::class, 'getStaffMembers'])->name('api.staff-members');
});

//for the staff
Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    Route::get('/maintenance', [StaffController::class, 'maintenance'])->name('staff.maintenance');
    Route::post('/maintenance/{id}/update-status', [StaffController::class, 'updateStatus'])->name('staff.maintenance.update-status');
});

