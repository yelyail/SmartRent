@extends('layouts.tenants')

@section('title', 'Maintenance_tenants - SmartRent')
@section('page-title', 'Maintenance')
@section('page-description', 'Track and manage maintenance requests across all properties.')

@section('header-actions')
    <button id="newRequestBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center space-x-2">
        <i class="fas fa-plus text-sm"></i>
        <span>New Request</span>
    </button>
@endsection

@section('content')
    <!-- Billing Overview -->
    @if($unpaidBills->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Outstanding Payments</h3>
                <p class="text-sm text-gray-500">Maintenance service invoices</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Total Due</p>
                <p class="text-2xl font-bold text-red-600">₱{{ number_format($totalOutstanding, 2) }}</p>
            </div>
        </div>
        
        <div class="space-y-4">
            @foreach($unpaidBills as $bill)
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-invoice text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $bill->bill_name }}</p>
                        <p class="text-xs text-gray-500">
                            @if($bill->maintenanceRequest)
                                {{ $bill->maintenanceRequest->title }}
                            @else
                                Maintenance Service
                            @endif
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            Due: {{ \Carbon\Carbon::parse($bill->due_date)->format('M d, Y') }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-gray-900">₱{{ number_format($bill->amount, 2) }}</p>
                    <button onclick="showPaymentModal({{ $bill->bill_id }}, {{ $bill->amount }})" 
                            class="mt-2 px-4 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                        Pay Now
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Stats Cards - Dynamic Data -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Requests -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wrench text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Requests</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $maintenanceRequests->count() }}</p>
                </div>                           
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-orange-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $maintenanceRequests->where('status', 'pending')->count() }}</p>
                </div>                            
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tools text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">In Progress</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $maintenanceRequests->where('status', 'in_progress')->count() }}</p>
                </div>                           
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Completed</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $maintenanceRequests->where('status', 'completed')->count() }}</p>
                </div>                          
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Search requests..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <div class="ml-4 flex space-x-3">
                <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <select id="priorityFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Maintenance Requests Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="requestsContainer">
        @forelse($maintenanceRequests as $request)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 maintenance-request-card" 
                 data-status="{{ $request->status }}" 
                 data-priority="{{ $request->priority }}"
                 data-search="{{ strtolower($request->title . ' ' . $request->description) }}">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex space-x-2">
                        <!-- Priority Badge -->
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($request->priority == 'high' || $request->priority == 'urgent') bg-red-100 text-red-800
                            @elseif($request->priority == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ strtoupper($request->priority) }}
                        </span>
                        <!-- Status Badge -->
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($request->status == 'pending') bg-orange-100 text-orange-800
                            @elseif($request->status == 'in_progress') bg-blue-100 text-blue-800
                            @elseif($request->status == 'completed') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ strtoupper(str_replace('_', ' ', $request->status)) }}
                        </span>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $request->title }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($request->description, 120) }}</p>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-home w-4 mr-3"></i>
                        <span>
                            @if($request->unit && $request->unit->property)
                                {{ $request->unit->property->property_name }} - Unit #{{ $request->unit->unit_num }}
                            @else
                                Unit Not Available
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar w-4 mr-3"></i>
                        <span>{{ $request->created_at->format('M j, Y') }}</span>
                        @if($request->assignedStaff)
                            <span class="ml-auto">Assigned to {{ $request->assignedStaff->first_name }} {{ $request->assignedStaff->last_name }}</span>
                        @else
                            <span class="ml-auto text-gray-400">Not assigned</span>
                        @endif
                    </div>
                    
                    <!-- Billing Information -->
                    @if($request->billing)
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Billing Status:</span>
                            <span class="text-sm font-semibold 
                                @if($request->billing->status == 'paid') text-green-600
                                @else text-orange-600 @endif">
                                {{ strtoupper($request->billing->status) }}
                            </span>
                        </div>
                        @if($request->billing->status == 'pending')
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Amount Due:</p>
                                <p class="text-lg font-bold text-gray-900">₱{{ number_format($request->billing->amount, 2) }}</p>
                            </div>
                            <button onclick="showPaymentModal({{ $request->billing->bill_id }}, {{ $request->billing->amount }})" 
                                    class="px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                                Pay Now
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Due by: {{ \Carbon\Carbon::parse($request->billing->due_date)->format('M d, Y') }}
                        </p>
                        @else
                        <div class="text-center py-2">
                            <i class="fas fa-check-circle text-green-500 text-lg mb-1"></i>
                            <p class="text-sm text-gray-600">Payment Completed</p>
                            <p class="text-xs text-gray-500">Maintenance marked as completed</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                
                <div class="flex space-x-3">
                    @if($request->status == 'pending')
                        <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2 view-details-btn" data-request-id="{{ $request->request_id }}">
                            <i class="fas fa-eye text-sm"></i>
                            <span>View Details</span>
                        </button>
                    @elseif($request->status == 'in_progress')
                        <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2 view-details-btn" data-request-id="{{ $request->request_id }}">
                            <i class="fas fa-eye text-sm"></i>
                            <span>View Details</span>
                        </button>
                    @else
                        <button class="flex-1 bg-gray-400 text-white py-2 px-4 rounded-lg font-medium cursor-not-allowed flex items-center justify-center space-x-2" disabled>
                            <i class="fas fa-check text-sm"></i>
                            <span>Request {{ ucfirst($request->status) }}</span>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-wrench text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Maintenance Requests</h3>
                <p class="text-gray-600 mb-6">You haven't submitted any maintenance requests yet.</p>
                <button id="newRequestEmptyBtn" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center space-x-2 mx-auto">
                    <i class="fas fa-plus text-sm"></i>
                    <span>Create Your First Request</span>
                </button>
            </div>
        @endforelse
    </div>
@endsection

@push('modals')
<!-- New Request Modal (keep existing) -->
<div id="newRequestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <!-- ... existing new request modal code ... -->
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Make Payment</h3>
            <form id="paymentForm" onsubmit="processPayment(event)">
                @csrf
                <input type="hidden" id="billId" name="bill_id">
                
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="text-center">
                            <p class="text-sm text-blue-700">Invoice Amount</p>
                            <p id="invoiceAmount" class="text-2xl font-bold text-blue-900">₱0.00</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Method <span class="text-red-500">*</span>
                        </label>
                        <select id="paymentMethod" name="payment_method" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Payment Method</option>
                            <option value="gcash">GCash (E-Cash)</option>
                            <option value="bank_transfer">Bank Transfer (Bank)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Amount to Pay <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                            <input type="number" id="amountPaid" name="amount_paid" min="0" step="0.01" required
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0.00">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Enter the amount you wish to pay</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Reference Number (Optional)
                        </label>
                        <input type="text" id="paymentReference" name="reference_number"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., Transaction ID, Check No.">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="paymentDate" name="payment_date" required
                               value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePaymentModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Submit Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-xl font-bold text-gray-900">Request Details</h3>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="detailsContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Modal functionality
    const modal = document.getElementById('newRequestModal');
    const newRequestBtn = document.getElementById('newRequestBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const form = document.getElementById('maintenanceRequestForm');
    const newRequestEmptyBtn = document.getElementById('newRequestEmptyBtn');

    // Open modal
    newRequestBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    // Empty state button
    if (newRequestEmptyBtn) {
        newRequestEmptyBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    }

    // Close modal functions
    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        form.reset();
        if (document.getElementById('priorityDisplay')) {
            document.getElementById('priorityDisplay').classList.add('hidden');
        }
    }

    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
    }

    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Payment Modal Functions
    function showPaymentModal(billId, amount) {
        document.getElementById('billId').value = billId;
        document.getElementById('invoiceAmount').textContent = '₱' + parseFloat(amount).toFixed(2);
        document.getElementById('amountPaid').value = amount;
        document.getElementById('amountPaid').max = amount;
        document.getElementById('paymentModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('paymentForm').reset();
    }

    async function processPayment(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i><span>Processing...</span>';
        submitBtn.disabled = true;

        try {
            const formData = new FormData(form);
            
            const response = await fetch('{{ route("tenants.maintenance.payment") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                showNotification(result.message || 'Payment successful!', 'success');
                closePaymentModal();
                
                // Reload the page to update status
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(result.message || 'Payment failed');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(error.message || 'An error occurred while processing payment', 'error');
        } finally {
            // Reset button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    // Details Modal Functions
    function showDetailsModal(requestId) {
        fetch(`/tenant/maintenance/${requestId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const request = data.request;
                    let detailsHtml = `
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Request Title</h4>
                                    <p class="text-lg font-semibold text-gray-900">${request.title}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Priority</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        ${request.priority === 'high' || request.priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                          request.priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'}">
                                        ${request.priority.charAt(0).toUpperCase() + request.priority.slice(1)}
                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Description</h4>
                                <p class="text-gray-700 mt-1">${request.description}</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Unit</h4>
                                    <p class="text-gray-700">${request.unit.property.property_name}</p>
                                    <p class="text-sm text-gray-500">Unit ${request.unit.unit_num}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        ${request.status === 'pending' ? 'bg-orange-100 text-orange-800' :
                                          request.status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                          request.status === 'completed' ? 'bg-green-100 text-green-800' :
                                          'bg-gray-100 text-gray-800'}">
                                        ${request.status.replace('_', ' ').charAt(0).toUpperCase() + request.status.replace('_', ' ').slice(1)}
                                    </span>
                                </div>
                            </div>
                            
                            ${request.billing ? `
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Billing Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500">Invoice ID</p>
                                        <p class="text-sm font-medium text-gray-900">${request.billing.bill_id}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Amount</p>
                                        <p class="text-sm font-bold text-gray-900">₱${parseFloat(request.billing.amount).toFixed(2)}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Status</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                            ${request.billing.status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                            ${request.billing.status.charAt(0).toUpperCase() + request.billing.status.slice(1)}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Due Date</p>
                                        <p class="text-sm text-gray-900">${new Date(request.billing.due_date).toLocaleDateString()}</p>
                                    </div>
                                </div>
                                ${request.billing.status === 'pending' ? `
                                <div class="mt-4 text-center">
                                    <button onclick="showPaymentModal(${request.billing.bill_id}, ${request.billing.amount})" 
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        Pay Now
                                    </button>
                                </div>
                                ` : ''}
                            </div>
                            ` : ''}
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Timeline</h4>
                                <div class="mt-3 space-y-3">
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="fas fa-calendar-plus text-blue-600 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Request Submitted</p>
                                            <p class="text-xs text-gray-500">${new Date(request.created_at).toLocaleString()}</p>
                                        </div>
                                    </div>
                                    
                                    ${request.assignedStaff ? `
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="fas fa-user-cog text-purple-600 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Staff Assigned</p>
                                            <p class="text-xs text-gray-500">${request.assignedStaff.first_name} ${request.assignedStaff.last_name}</p>
                                        </div>
                                    </div>
                                    ` : ''}
                                    
                                    ${request.completed_at ? `
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="fas fa-flag-checkered text-green-600 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Request Completed</p>
                                            <p class="text-xs text-gray-500">${new Date(request.completed_at).toLocaleString()}</p>
                                        </div>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                    document.getElementById('detailsContent').innerHTML = detailsHtml;
                    document.getElementById('detailsModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            });
    }

    function closeDetailsModal() {
        document.getElementById('detailsModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // View Details button handlers
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            showDetailsModal(requestId);
        });
    });

    // Notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 'bg-blue-500'
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

    // Search and filter functionality
    document.getElementById('searchInput').addEventListener('input', filterRequests);
    document.getElementById('statusFilter').addEventListener('change', filterRequests);
    document.getElementById('priorityFilter').addEventListener('change', filterRequests);

    function filterRequests() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const priorityFilter = document.getElementById('priorityFilter').value;
        
        const cards = document.querySelectorAll('.maintenance-request-card');
        
        cards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('p.text-gray-600').textContent.toLowerCase();
            const status = card.getAttribute('data-status');
            const priority = card.getAttribute('data-priority');
            
            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;
            const matchesPriority = !priorityFilter || priority === priorityFilter;
            
            card.style.display = matchesSearch && matchesStatus && matchesPriority ? '' : 'none';
        });
    }
</script>
@endpush