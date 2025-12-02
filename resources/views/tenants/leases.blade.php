@extends('layouts.tenants')

@section('title', 'My Leases - SmartRent')
@section('page-title', 'My Leases')
@section('page-description', 'View your rental applications and lease status.')

@section('content')
    <div class="space-y-6">
        @foreach($leases as $lease)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" data-lease-id="{{ $lease->lease_id }}">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <img class="h-16 w-16 rounded-lg object-cover" src="{{ asset('storage/' . $lease->property->property_image) }}" alt="{{ $lease->property->property_name }}">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $lease->property->property_name }}</h3>
                            <p class="text-sm text-gray-500">{{ $lease->property->property_address }}</p>
                            <p class="text-sm text-gray-600">₱{{ number_format($lease->rent_amount, 2) }}/month</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            {{ $lease->status === 'activate' ? 'bg-green-100 text-green-800' : 
                               ($lease->status === 'approved' ? 'bg-blue-100 text-blue-800' : 
                               ($lease->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                               'bg-red-100 text-red-800')) }}">
                            {{ ucfirst($lease->status) }}
                        </span>
                        @if($lease->status === 'approved')
                        <button onclick="openDepositModal({{ $lease->lease_id }}, '{{ addslashes($lease->property->property_name) }}', '{{ addslashes($lease->property->property_address) }}', '₱{{ number_format($lease->rent_amount, 2) }}', '₱{{ number_format($lease->deposit_amount, 2) }}')" 
                                class="mt-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Pay Deposit
                        </button>
                        @elseif($lease->status === 'activate')
                            @php
                                // Check if deposit was paid by looking for deposit payment
                                $depositPaid = \App\Models\Payment::where('lease_id', $lease->lease_id)
                                    ->whereHas('billing', function($query) {
                                        $query->where('bill_name', 'like', 'Security Deposit%');
                                    })
                                    ->exists();
                            @endphp
                            
                            @if($depositPaid)
                            <button onclick="openMonthlyModal({{ $lease->lease_id }}, '{{ addslashes($lease->property->property_name) }}', '{{ addslashes($lease->property->property_address) }}', '₱{{ number_format($lease->rent_amount, 2) }}')" 
                                    class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                Pay Monthly Rent
                            </button>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Lease Period:</span>
                        <p class="font-medium">
                            {{ \Carbon\Carbon::parse($lease->start_date)->format('M d, Y') }} - 
                            {{ \Carbon\Carbon::parse($lease->end_date)->format('M d, Y') }}
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-600">Security Deposit:</span>
                        <p class="font-medium">₱{{ number_format($lease->deposit_amount, 2) }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Deposit Status:</span>
                        @php
                            $depositPaidStatus = \App\Models\Payment::where('lease_id', $lease->lease_id)
                                ->whereHas('billing', function($query) {
                                    $query->where('bill_name', 'like', 'Security Deposit%');
                                })
                                ->exists();
                        @endphp
                        <p class="font-medium {{ $depositPaidStatus ? 'text-green-600' : 'text-red-600' }}">
                            {{ $depositPaidStatus ? 'Paid' : 'Pending' }}
                        </p>
                    </div>
                </div>

                <!-- Display Monthly Rent Status -->
                @if($lease->status === 'activate')
                @php
                    // Get the latest rent payment to determine next due date
                    $latestRentPayment = \App\Models\Billing::where('lease_id', $lease->lease_id)
                        ->where('bill_name', 'like', 'Monthly Rent%')
                        ->orderBy('due_date', 'desc')
                        ->first();
                    
                    // Get the last payment made (rent or deposit)
                    $lastPayment = \App\Models\Payment::where('lease_id', $lease->lease_id)
                        ->with('billing')
                        ->orderBy('payment_date', 'desc')
                        ->first();
                    
                    if ($latestRentPayment) {
                        $nextDueDate = \Carbon\Carbon::parse($latestRentPayment->due_date)->addMonth();
                    } else {
                        $nextDueDate = \Carbon\Carbon::parse($lease->start_date)->addMonth();
                    }
                    
                    $now = \Carbon\Carbon::now();
                    $isOverdue = $now->greaterThan($nextDueDate);
                    
                    // Calculate late fee (1% of monthly rent)
                    $lateFee = $isOverdue ? $lease->rent_amount * 0.01 : 0;
                    $totalWithLateFee = $lease->rent_amount + $lateFee;
                @endphp
                
                <!-- Last Payment Information -->
                @if($lastPayment)
                <div class="mt-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Last Payment Made</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Payment Type:</span>
                            <p class="font-medium">
                                @if(str_contains($lastPayment->billing->bill_name, 'Security Deposit'))
                                    Security Deposit
                                @else
                                    Monthly Rent
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-600">Amount Paid:</span>
                            <p class="font-medium text-green-600">₱{{ number_format($lastPayment->amount_paid, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Payment Date:</span>
                            <p class="font-medium">
                                {{ \Carbon\Carbon::parse($lastPayment->payment_date)->format('M d, Y') }}
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-600">Reference No:</span>
                            <p class="font-medium text-blue-600">{{ $lastPayment->reference_no }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Next Rent Due:</span>
                        <p class="font-medium">
                            <span class="{{ $isOverdue ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $nextDueDate->format('M d, Y') }}
                                @if($isOverdue)
                                    <span class="text-xs text-red-500">(Overdue)</span>
                                @endif
                            </span>
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-600">Rent Status:</span>
                        <p class="font-medium {{ $isOverdue ? 'text-red-600' : 'text-green-600' }}">
                            {{ $isOverdue ? 'Payment Due' : 'Payable Now' }}
                        </p>
                    </div>
                </div>

                <!-- Late Fee Information -->
                @if($isOverdue)
                <div class="mt-3 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                            <p class="text-sm text-red-800 font-medium">
                                Late Fee Applied: ₱{{ number_format($lateFee, 2) }} (1% of monthly rent)
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-red-800">
                                Total Due: ₱{{ number_format($totalWithLateFee, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Payment Information -->
                <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        <p class="text-sm text-blue-800">
                            @if($isOverdue)
                                <span class="text-red-600 font-medium">Your rent payment is overdue.</span> A 1% late fee (₱{{ number_format($lateFee, 2) }}) has been applied. Total due: ₱{{ number_format($totalWithLateFee, 2) }}
                            @else
                                You can pay your rent at any time. Payments made early will be applied to the next due period.
                            @endif
                        </p>
                    </div>
                </div>
                @endif

                @if($lease->status === 'pending')
                <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-yellow-600 mr-2"></i>
                        <p class="text-sm text-yellow-800">
                            Waiting for landlord approval. You'll be notified once your request is reviewed.
                        </p>
                    </div>
                </div>
                @endif

                @if($lease->status === 'approved')
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        <p class="text-sm text-blue-800">
                            Your lease has been approved! Please pay the security deposit to activate your lease.
                        </p>
                    </div>
                </div>
                @endif

                @if($lease->status === 'activate')
                <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <p class="text-sm text-green-800">
                            Your lease is activated! You can now access the property and smart devices.
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach

        @if($leases->isEmpty())
        <div class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">📝</div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Lease Applications</h3>
            <p class="text-gray-500 mb-6">You haven't applied for any properties yet.</p>
            <a href="{{ route('tenants.properties') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center space-x-2">
                <i class="fas fa-home"></i>
                <span>Browse Properties</span>
            </a>
        </div>
        @endif
    </div>
@endsection

@push('modals')
    <!-- Deposit Payment Modal -->
    <div id="depositModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Pay Security Deposit</h2>
                <button type="button" onclick="closeDepositModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h3 id="depositPropertyName" class="font-semibold text-gray-900"></h3>
                    <p id="depositPropertyAddress" class="text-sm text-gray-600"></p>
                </div>
                
                <div class="space-y-3 text-sm text-gray-600 mb-4">
                    <div class="flex justify-between">
                        <span>Monthly Rent:</span>
                        <span id="depositRentAmount" class="font-medium text-gray-900"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Security Deposit:</span>
                        <span id="depositAmount" class="font-medium text-gray-900"></span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2">
                        <span>Total to Pay:</span>
                        <span id="depositTotalAmount" class="font-medium text-green-600"></span>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-yellow-600 mr-2"></i>
                        <p class="text-sm text-yellow-800">
                            The security deposit will be refunded at the end of your lease term, subject to property inspection.
                        </p>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="deposit_payment_method" value="gcash" class="mr-2" checked>
                            <span class="text-sm text-gray-700">GCash</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="deposit_payment_method" value="bank_transfer" class="mr-2">
                            <span class="text-sm text-gray-700">Bank Transfer</span>
                        </label>
                    </div>
                </div>

                <!-- Reference Number Input -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reference Number *</label>
                    <input type="text" id="depositReferenceNumberInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter transaction reference number" required>
                    <p class="text-xs text-gray-500 mt-1">Please enter your transaction reference number from GCash or Bank Transfer</p>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200">
                <button type="button" onclick="closeDepositModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="button" id="confirmDepositBtn" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center space-x-2">
                    <i class="fas fa-lock text-sm"></i>
                    <span>Pay Securely</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Monthly Rent Payment Modal -->
    <div id="monthlyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Pay Monthly Rent</h2>
                <button type="button" onclick="closeMonthlyModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h3 id="monthlyPropertyName" class="font-semibold text-gray-900"></h3>
                    <p id="monthlyPropertyAddress" class="text-sm text-gray-600"></p>
                </div>
                
                <div class="space-y-3 text-sm text-gray-600 mb-4">
                    <div class="flex justify-between">
                        <span>Monthly Rent:</span>
                        <span id="monthlyRentAmount" class="font-medium text-gray-900"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Due Date:</span>
                        <span id="monthlyDueDate" class="font-medium text-gray-900"></span>
                    </div>
                    <div id="monthlyLateFeeSection" class="hidden">
                        <div class="flex justify-between text-red-600">
                            <span>Late Fee (1%):</span>
                            <span id="monthlyLateFee" class="font-medium"></span>
                        </div>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2">
                        <span class="font-medium">Total to Pay:</span>
                        <span id="monthlyTotalAmount" class="font-medium text-green-600"></span>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                        <p class="text-sm text-blue-800">
                            You can pay your rent at any time. Late payments incur a 1% late fee.
                        </p>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="monthly_payment_method" value="gcash" class="mr-2" checked>
                            <span class="text-sm text-gray-700">GCash</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="monthly_payment_method" value="bank_transfer" class="mr-2">
                            <span class="text-sm text-gray-700">Bank Transfer</span>
                        </label>
                    </div>
                </div>

                <!-- Reference Number Input -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reference Number *</label>
                    <input type="text" id="monthlyReferenceNumberInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter transaction reference number" required>
                    <p class="text-xs text-gray-500 mt-1">Please enter your transaction reference number from GCash or Bank Transfer</p>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200">
                <button type="button" onclick="closeMonthlyModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="button" id="confirmMonthlyBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center space-x-2">
                    <i class="fas fa-lock text-sm"></i>
                    <span>Pay Rent</span>
                </button>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
<script>
let currentLeaseId = null;
let currentModalType = null;

function openDepositModal(leaseId, propertyName, propertyAddress, rentAmount, depositAmount) {
    currentLeaseId = leaseId;
    currentModalType = 'deposit';
    
    // Populate modal directly from parameters
    document.getElementById('depositPropertyName').textContent = propertyName || 'Property';
    document.getElementById('depositPropertyAddress').textContent = propertyAddress || 'Address not available';
    document.getElementById('depositRentAmount').textContent = rentAmount || '₱0.00';
    document.getElementById('depositAmount').textContent = depositAmount || '₱0.00';
    document.getElementById('depositTotalAmount').textContent = depositAmount || '₱0.00';
    
    // Reset reference number input
    document.getElementById('depositReferenceNumberInput').value = '';
    
    document.getElementById('depositModal').classList.remove('hidden');
    document.getElementById('depositModal').classList.add('flex');
}

function openMonthlyModal(leaseId, propertyName, propertyAddress, rentAmount) {
    currentLeaseId = leaseId;
    currentModalType = 'monthly';
    
    // Extract numeric value from rent amount string (e.g., "₱10,000.00" -> 10000)
    const rentValue = parseFloat(rentAmount.replace(/[₱,]/g, ''));
    
    // Populate modal directly from parameters
    document.getElementById('monthlyPropertyName').textContent = propertyName || 'Property';
    document.getElementById('monthlyPropertyAddress').textContent = propertyAddress || 'Address not available';
    document.getElementById('monthlyRentAmount').textContent = rentAmount || '₱0.00';
    
    // Calculate next due date (one month from last payment or from lease start)
    const nextDueDate = new Date();
    nextDueDate.setMonth(nextDueDate.getMonth() + 1);
    
    document.getElementById('monthlyDueDate').textContent = nextDueDate.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
    
    // Check if payment is overdue
    const today = new Date();
    const isOverdue = today > nextDueDate;
    
    // Calculate late fee (1% of monthly rent)
    const lateFee = isOverdue ? rentValue * 0.01 : 0;
    const totalAmount = rentValue + lateFee;
    
    // Update modal with late fee information
    const lateFeeSection = document.getElementById('monthlyLateFeeSection');
    const monthlyLateFee = document.getElementById('monthlyLateFee');
    const monthlyTotalAmount = document.getElementById('monthlyTotalAmount');
    
    if (isOverdue && lateFee > 0) {
        lateFeeSection.classList.remove('hidden');
        monthlyLateFee.textContent = `₱${lateFee.toFixed(2)}`;
        monthlyTotalAmount.textContent = `₱${totalAmount.toFixed(2)}`;
    } else {
        lateFeeSection.classList.add('hidden');
        monthlyTotalAmount.textContent = rentAmount || '₱0.00';
    }
    
    // Reset reference number input
    document.getElementById('monthlyReferenceNumberInput').value = '';
    
    document.getElementById('monthlyModal').classList.remove('hidden');
    document.getElementById('monthlyModal').classList.add('flex');
}

function closeDepositModal() {
    document.getElementById('depositModal').classList.add('hidden');
    document.getElementById('depositModal').classList.remove('flex');
    currentLeaseId = null;
    currentModalType = null;
}

function closeMonthlyModal() {
    document.getElementById('monthlyModal').classList.add('hidden');
    document.getElementById('monthlyModal').classList.remove('flex');
    currentLeaseId = null;
    currentModalType = null;
}

// Handle deposit payment
document.getElementById('confirmDepositBtn').addEventListener('click', function() {
    if (!currentLeaseId) return;
    
    const button = this;
    const originalText = button.innerHTML;
    
    // Get selected payment method and reference number
    const selectedPaymentMethod = document.querySelector('input[name="deposit_payment_method"]:checked').value;
    const referenceNumber = document.getElementById('depositReferenceNumberInput').value.trim();
    
    // Validate reference number
    if (!referenceNumber) {
        showNotification('Please enter a reference number for your payment.', 'error');
        return;
    }
    
    // Show loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i><span>Processing Payment...</span>';
    button.disabled = true;
    
    fetch(`/tenants/leases/${currentLeaseId}/pay-deposit`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            payment_method: selectedPaymentMethod,
            reference_number: referenceNumber
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Deposit paid successfully! Your lease is now activate.', 'success');
            closeDepositModal();
            
            // Reload page to update status
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showNotification(data.message || 'Failed to process payment', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
});

// Handle monthly rent payment
document.getElementById('confirmMonthlyBtn').addEventListener('click', function() {
    if (!currentLeaseId) return;
    
    const button = this;
    const originalText = button.innerHTML;
    
    // Get selected payment method and reference number
    const selectedPaymentMethod = document.querySelector('input[name="monthly_payment_method"]:checked').value;
    const referenceNumber = document.getElementById('monthlyReferenceNumberInput').value.trim();
    
    // Validate reference number
    if (!referenceNumber) {
        showNotification('Please enter a reference number for your payment.', 'error');
        return;
    }
    
    // Show loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i><span>Processing Payment...</span>';
    button.disabled = true;
    
    fetch(`/tenants/leases/${currentLeaseId}/pay-rent`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            payment_method: selectedPaymentMethod,
            reference_number: referenceNumber
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Monthly rent paid successfully!', 'success');
            closeMonthlyModal();
            
            // Reload page to update status
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showNotification(data.message || 'Failed to process payment', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
});

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-transform duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Close modals when clicking outside
document.getElementById('depositModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDepositModal();
    }
});

document.getElementById('monthlyModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMonthlyModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (currentModalType === 'deposit') {
            closeDepositModal();
        } else if (currentModalType === 'monthly') {
            closeMonthlyModal();
        }
    }
});
</script>
@endpush