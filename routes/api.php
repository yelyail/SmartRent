<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Payment history API routes
    Route::get('/payments/history', [PaymentController::class, 'getPaymentHistory']);
    Route::get('/billings', [PaymentController::class, 'getUserBillings']);
    
    // PDF export routes
    Route::get('/payments/{paymentId}/invoice-pdf', [PaymentController::class, 'exportInvoicePdf']);
    Route::get('/payments/{paymentId}/invoice-details', [PaymentController::class, 'getInvoiceDetails']);
    Route::get('/payments/export-report-pdf', [PaymentController::class, 'exportPaymentReportPdf']);
});
