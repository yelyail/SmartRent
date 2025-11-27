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

            // Update lease status to active (no deposit_paid column)
            $lease->update([
                'status' => 'active'
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
        Log::info('=== PAY RENT START ===', ['leaseId' => $leaseId, 'user_id' => Auth::id()]);

        try {
            // Check if lease exists and is active
            $lease = Leases::where('user_id', Auth::id())
                ->where('lease_id', $leaseId)
                ->where('status', 'active')
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

    /**
     * Generate reference number for deposit payments
     */
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

    /**
     * Generate reference number for rent payments
     */
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
}