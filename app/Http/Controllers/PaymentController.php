<?php

namespace App\Http\Controllers;

use App\Models\Leases;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;


class PaymentController extends Controller
{
    // For deposit payment
    public function payDeposit(Request $request, $leaseId)
    {
        $lease = Leases::where('user_id', Auth::id())
            ->where('lease_id', $leaseId)
            ->firstOrFail();

        if ($lease->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Lease must be approved by landlord before paying deposit'
            ], 400);
        }

        // Check if deposit was already paid by looking for existing deposit payments
        $existingDepositPayment = Payment::where('lease_id', $lease->lease_id)
            ->whereHas('billing', function($query) {
                $query->where('bill_name', 'like', 'Security Deposit%');
            })
            ->exists();

        if ($existingDepositPayment) {
            return response()->json([
                'success' => false,
                'message' => 'Deposit already paid for this lease'
            ], 400);
        }

        try {
            DB::beginTransaction();
            
            $paymentMethodMap = [
                'bank_transfer' => 'bank',
                'gcash' => 'e-cash'
            ];

            $paymentMethod = $paymentMethodMap[$request->payment_method] ?? 'bank';

            // Generate reference number based on payment method
            $referenceNo = $this->generateDepositReferenceNumber($request->payment_method, $request->reference_number);

            // Update lease status to active - ENCLOSE IN QUOTES
            $lease->update([
                'status' => 'activate' 
            ]);

            // Create billing record for deposit
            $billing = Billing::create([
                'lease_id' => $lease->lease_id,
                'bill_name' => 'Security Deposit - ' . $lease->property->property_name,
                'bill_period' => 'One-time',
                'due_date' => now()->format('Y-m-d'),
                'late_fee' => 0.00,
                'overdue_amount_percent' => 0,
                'amount' => $lease->deposit_amount,
                'status' => 'paid',
            ]);

            // Create payment record for the deposit
            $payment = Payment::create([
                'bill_id' => $billing->bill_id,
                'lease_id' => $lease->lease_id,
                'amount_paid' => $lease->deposit_amount,
                'payment_date' => now(),
                'payment_method' => $paymentMethod,
                'reference_no' => $referenceNo,
                'transaction_type' => 'deposit',
            ]);

            // Create invoice for the deposit
            $invoice = Invoices::create([
                'lease_id' => $lease->lease_id,
                'invoice_no' => 'INV-DEP-' . now()->format('YmdHis'),
                'subtotal' => $lease->deposit_amount,
                'late_fees' => 0.00,
                'other_charges' => 0.00,
                'total_amount' => $lease->deposit_amount,
                'status' => 'paid',
                'invoice_date' => now()->format('Y-m-d'),
                'due_date' => now()->format('Y-m-d'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Deposit paid successfully! Your lease is now active.',
                'lease' => $lease
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Deposit payment failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process deposit payment: ' . $e->getMessage()
            ], 500);
        }
    }

    // For rent payment
    public function payRent(Request $request, $leaseId)
    {
        try {
            // Check if lease exists and is active
            $lease = Leases::where('user_id', Auth::id())
                ->where('lease_id', $leaseId)
                ->where('status', 'activate')
                ->first();

            if (!$lease) {
                Log::error('Lease not found or not active', [
                    'leaseId' => $leaseId,
                    'user_id' => Auth::id(),
                    'exists' => Leases::where('lease_id', $leaseId)->exists(),
                    'status' => Leases::where('lease_id', $leaseId)->value('status'),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Lease not found or not active'
                ], 404);
            }

            // Check if deposit was paid by looking for deposit payment
            $depositPaid = Payment::where('lease_id', $lease->lease_id)
                ->whereHas('billing', function($query) {
                    $query->where('bill_name', 'like', 'Security Deposit%');
                })
                ->exists();

            if (!$depositPaid) {
                Log::error('Deposit not paid for lease', [
                    'lease_id' => $lease->lease_id,
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Security deposit must be paid before paying rent'
                ], 400);
            }

            Log::info('Lease found and deposit paid', [
                'lease_id' => $lease->lease_id,
                'status' => $lease->status,
                'property_id' => $lease->property_id
            ]);

            // Debug: Check property relationship
            if (!$lease->property) {
                Log::error('Property not found for lease', [
                    'lease_id' => $lease->lease_id,
                    'property_id' => $lease->property_id
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Property information not found'
                ], 404);
            }

            Log::info('Property found', [
                'property_id' => $lease->property->property_id,
                'property_name' => $lease->property->property_name
            ]);

            // Validate reference number
            if (empty($request->reference_number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reference number is required for payment'
                ], 400);
            }

            DB::beginTransaction();
            
            $paymentMethodMap = [
                'bank_transfer' => 'bank',
                'gcash' => 'e-cash'
            ];

            $paymentMethod = $paymentMethodMap[$request->payment_method] ?? 'bank';

            // Generate reference number
            $referenceNo = $this->generateRentReferenceNumber($request->payment_method, $request->reference_number);

            // Get the latest rent payment to determine next due date
            $latestRentPayment = Billing::where('lease_id', $lease->lease_id)
                ->where('bill_name', 'like', 'Monthly Rent%')
                ->orderBy('due_date', 'desc')
                ->first();

            if ($latestRentPayment) {
                $dueDate = \Carbon\Carbon::parse($latestRentPayment->due_date)->addMonth();
            } else {
                $dueDate = \Carbon\Carbon::parse($lease->start_date)->addMonth();
            }

            // Check if payment is overdue and calculate late fee
            $now = now();
            $isOverdue = $now->greaterThan($dueDate);
            $lateFee = $isOverdue ? $lease->rent_amount * 0.01 : 0.00;
            $totalAmount = $lease->rent_amount + $lateFee;

            // Create billing record
            $billingData = [
                'lease_id' => $lease->lease_id,
                'bill_name' => 'Monthly Rent - ' . $lease->property->property_name . ' - ' . $dueDate->format('F Y'),
                'bill_period' => $dueDate->format('F Y'),
                'due_date' => $dueDate->format('Y-m-d'),
                'late_fee' => $lateFee,
                'overdue_amount_percent' => 1,
                'amount' => $totalAmount,
                'status' => 'paid',
            ];

            Log::info('Creating billing record', $billingData);
            $billing = Billing::create($billingData);

            // Create payment record
            $paymentData = [
                'bill_id' => $billing->bill_id,
                'lease_id' => $lease->lease_id,
                'amount_paid' => $totalAmount,
                'payment_date' => now(),
                'payment_method' => $paymentMethod,
                'reference_no' => $referenceNo,
                'transaction_type' => 'rent',
            ];

            Log::info('Creating payment record', $paymentData);
            $payment = Payment::create($paymentData);

            // Create invoice record
            $invoiceData = [
                'lease_id' => $lease->lease_id,
                'invoice_no' => 'INV-RENT-' . now()->format('YmdHis'),
                'subtotal' => $lease->rent_amount,
                'late_fees' => $lateFee,
                'other_charges' => 0.00,
                'total_amount' => $totalAmount,
                'status' => 'paid',
                'invoice_date' => now()->format('Y-m-d'),
                'due_date' => $dueDate->format('Y-m-d'),
            ];

            Log::info('Creating invoice record', $invoiceData);
            $invoice = Invoices::create($invoiceData);

            DB::commit();

            $message = $isOverdue 
                ? "Monthly rent paid successfully! A 1% late fee of ₱" . number_format($lateFee, 2) . " was applied. Next payment due on " . $dueDate->addMonth()->format('M d, Y')
                : "Monthly rent paid successfully! Next payment due on " . $dueDate->addMonth()->format('M d, Y');

            Log::info('=== PAY RENT SUCCESS ===', ['message' => $message]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'lease' => $lease
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== PAY RENT FAILED ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'leaseId' => $leaseId,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process rent payment: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateDepositReferenceNumber($paymentMethod, $userReference)
    {
        if ($paymentMethod === 'gcash') {
            return 'DEP-GCASH-' . $userReference;
        }
        
        if ($paymentMethod === 'bank_transfer') {
            return 'DEP-BANK-' . $userReference;
        }
        
        return 'DEP-' . $userReference;
    }
    private function generateRentReferenceNumber($paymentMethod, $userReference)
    {
        if ($paymentMethod === 'gcash') {
            return 'RENT-GCASH-' . $userReference;
        }
        
        if ($paymentMethod === 'bank_transfer') {
            return 'RENT-BANK-' . $userReference;
        }
        
        return 'RENT-' . $userReference;
    }

    public function exportInvoicePdf($paymentId)
    {
        try {
            $user = Auth::user();
            $leaseIds = $user->leases()->pluck('lease_id');
            
            $payment = Payment::with(['billing', 'lease.property'])
                ->whereIn('lease_id', $leaseIds)
                ->where('payment_id', $paymentId)
                ->firstOrFail();

            $data = [
                'payment' => $payment,
                'user' => $user,
                'invoice_no' => 'INV-' . $payment->payment_id . '-' . date('Y'),
                'invoice_date' => now()->format('F d, Y'),
            ];

            // Use the PDF facade instead of app('dompdf.wrapper')
            $pdf = Pdf::loadView('pdf.invoice', $data);
            
            return $pdf->download('invoice-' . $data['invoice_no'] . '.pdf');
            
        } catch (\Exception $e) {
            Log::error('Error exporting invoice PDF: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to generate PDF',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function exportPaymentReportPdf(Request $request)
    {
        try {
            $user = Auth::user();
            // Use user_id instead of id
            $leaseIds = $user->leases()->pluck('lease_id');
            
            // Rest of the method remains the same...
            $paymentsQuery = Payment::with(['billing', 'lease.property'])
                ->whereIn('lease_id', $leaseIds);

            // ... rest of the method
        } catch (\Exception $e) {
            Log::error('Error exporting payment report PDF: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to generate PDF report',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getInvoiceDetails($paymentId)
    {
        try {
            $user = Auth::user();
            $leaseIds = $user->leases()->pluck('lease_id');
            
            $payment = Payment::with(['billing', 'lease.property'])
                ->whereIn('lease_id', $leaseIds)
                ->where('payment_id', $paymentId)
                ->firstOrFail();

            return response()->json([
                'payment' => $payment,
                'user' => $user,
                'invoice_no' => 'INV-' . $payment->payment_id . '-' . date('Y'),
                'invoice_date' => now()->format('F d, Y'),
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting invoice details: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to load invoice details',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getPaymentHistory(Request $request)
    {
        try {
            // Debug: Log all authentication information
            Log::info('=== AUTH DEBUG START ===');
            Log::info('Auth::check(): ' . (Auth::check() ? 'true' : 'false'));
            Log::info('Auth::id(): ' . Auth::id());
            
            $user = Auth::user();
            
            // Debug the user object
            Log::info('User object type: ' . gettype($user));
            Log::info('User object class: ' . ($user ? get_class($user) : 'null'));
            
            if (!$user) {
                Log::error('USER NOT AUTHENTICATED');
                return response()->json([
                    'error' => 'User not authenticated',
                    'payments' => [],
                    'billings' => []
                ], 401);
            }

            // Use user_id instead of id since that's your primary key
            Log::info('User authenticated:', [
                'user_id' => $user->user_id, // Changed from id to user_id
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'user_email' => $user->email
            ]);

            // Get user's lease IDs using direct query - FIXED: use user_id
            $leaseIds = \App\Models\Leases::where('user_id', $user->user_id)->pluck('lease_id');
            
            Log::info('Lease query result:', [
                'user_id' => $user->user_id, // Changed from id to user_id
                'lease_ids' => $leaseIds->toArray(),
                'lease_count' => $leaseIds->count()
            ]);

            // If user has no leases, return empty arrays with message
            if ($leaseIds->isEmpty()) {
                return response()->json([
                    'payments' => [],
                    'billings' => [],
                    'message' => 'No leases found for user. Payment history will be available once you have an active lease.',
                    'success' => true
                ]);
            }
            
            // Base query for payments
            $paymentsQuery = Payment::with(['billing', 'lease.property'])
                ->whereIn('lease_id', $leaseIds)
                ->orderBy('payment_date', 'desc');
            
            // Apply filters
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $paymentsQuery->where(function($query) use ($search) {
                    $query->where('reference_no', 'like', "%{$search}%")
                        ->orWhereHas('billing', function($q) use ($search) {
                            $q->where('bill_name', 'like', "%{$search}%");
                        });
                });
            }
            
            if ($request->has('status') && $request->status) {
                $paymentsQuery->whereHas('billing', function($query) use ($request) {
                    $query->where('status', $request->status);
                });
            }
            
            if ($request->has('type') && $request->type) {
                $paymentsQuery->where('transaction_type', $request->type);
            }
            
            if ($request->has('date_from') && $request->date_from) {
                $paymentsQuery->whereDate('payment_date', '>=', $request->date_from);
            }
            
            if ($request->has('date_to') && $request->date_to) {
                $paymentsQuery->whereDate('payment_date', '<=', $request->date_to);
            }
            
            $payments = $paymentsQuery->get();
            
            // Get billings for statistics
            $billings = Billing::whereIn('lease_id', $leaseIds)->get();
            
            Log::info('=== AUTH DEBUG END - SUCCESS ===');

            return response()->json([
                'payments' => $payments,
                'billings' => $billings,
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getPaymentHistory: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Failed to load payment history',
                'message' => $e->getMessage(),
                'payments' => [],
                'billings' => []
            ], 500);
        }
    }

    public function getUserBillings(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'billings' => []
                ], 401);
            }

            // Check if leases relationship exists
            if (!method_exists($user, 'leases')) {
                return response()->json([
                    'error' => 'User model configuration error',
                    'billings' => []
                ], 500);
            }

            $leaseIds = $user->leases()->pluck('lease_id');

            // If user has no leases, return empty array
            if ($leaseIds->isEmpty()) {
                return response()->json([
                    'billings' => [],
                    'message' => 'No leases found for user',
                    'success' => true
                ]);
            }

            $billings = Billing::with(['payments', 'lease.property'])
                ->whereIn('lease_id', $leaseIds)
                ->orderBy('due_date', 'desc')
                ->get();
            
            return response()->json([
                'billings' => $billings,
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getUserBillings: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Failed to load billings',
                'message' => $e->getMessage(),
                'billings' => []
            ], 500);
        }
    }

    
}