@extends('layouts.tenants')

@section('title', 'Analytics & Reports - SmartRent')
@section('page-title', 'Analytics & Reports')
@section('page-description', 'View your payment history, reports, and analytics.')

@section('header-actions')
<div class="flex space-x-3">
    <div class="flex space-x-2">
        <input type="date" id="dateFrom" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <span class="flex items-center text-gray-500">to</span>
        <input type="date" id="dateTo" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <button id="filterBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">
            Filter
        </button>
    </div>
    <button id="exportPdfBtn" class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition-colors flex items-center space-x-2">
        <i class="fas fa-file-pdf text-sm"></i>
        <span>Export PDF</span>
    </button>
</div>
@endsection

@section('content')
<!-- Analytics Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Payments -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-receipt text-green-600 text-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Payments</p>
                <p class="text-3xl font-bold text-gray-900" id="totalPayments">0</p>
            </div>                           
        </div>
    </div>

    <!-- Total Amount -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-blue-600 text-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                <p class="text-3xl font-bold text-gray-900" id="totalAmount">₱0.00</p>
            </div>                            
        </div>
    </div>

    <!-- This Month -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-alt text-orange-600 text-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">This Month</p>
                <p class="text-3xl font-bold text-gray-900" id="thisMonth">₱0.00</p>
            </div>                           
        </div>
    </div>

    <!-- Pending Payments -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-red-600 text-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Pending Bills</p>
                <p class="text-3xl font-bold text-gray-900" id="pendingPayments">0</p>
            </div>                          
        </div>
    </div>
</div>

<!-- Payment History Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Payment History</h3>
        <div class="flex space-x-3">
            <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                <option value="">All Status</option>
                <option value="paid">Paid</option>
                <option value="pending">Pending</option>
                <option value="overdue">Overdue</option>
            </select>
            <select id="typeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                <option value="">All Types</option>
                <option value="rent">Rent</option>
                <option value="deposit">Deposit</option>
                <option value="utility">Utility</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <div class="relative max-w-md">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="searchInput" placeholder="Search payments..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
    </div>

    <!-- Payment History Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="paymentTableBody">
                <!-- Payment rows will be populated via JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="hidden text-center py-12">
        <i class="fas fa-receipt text-4xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No payments found</h3>
        <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="text-center py-8">
        <div class="inline-flex items-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-gray-600">Loading payments...</span>
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Invoice Preview Modal -->
<div id="invoiceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-invoice text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Invoice Details</h2>
                    <p class="text-sm text-gray-500" id="invoiceNumber">INV-001</p>
                </div>
            </div>
            <button id="closeInvoiceModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Invoice Content -->
        <div class="p-6" id="invoiceContent">
            <!-- Invoice content will be populated here -->
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200">
            <button id="printInvoiceBtn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors flex items-center space-x-2">
                <i class="fas fa-print text-sm"></i>
                <span>Print</span>
            </button>
            <button id="downloadInvoiceBtn" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center space-x-2">
                <i class="fas fa-download text-sm"></i>
                <span>Download PDF</span>
            </button>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Get CSRF token from meta tag
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    // Format currency
    function formatCurrency(amount) {
        if (!amount) return '₱0.00';
        return '₱' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    // Format date
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        } catch (e) {
            return 'Invalid Date';
        }
    }

    // Get status badge class
    function getStatusBadge(status) {
        const statusClasses = {
            paid: 'bg-green-100 text-green-800',
            pending: 'bg-orange-100 text-orange-800',
            overdue: 'bg-red-100 text-red-800'
        };
        return statusClasses[status] || 'bg-gray-100 text-gray-800';
    }

    // Get type badge class
    function getTypeBadge(type) {
        const typeClasses = {
            rent: 'bg-blue-100 text-blue-800',
            deposit: 'bg-purple-100 text-purple-800',
            utility: 'bg-yellow-100 text-yellow-800',
            maintenance: 'bg-red-100 text-red-800'
        };
        return typeClasses[type] || 'bg-gray-100 text-gray-800';
    }

    // Determine transaction type from bill name
    function getTransactionType(billName) {
        if (!billName) return 'other';
        
        const name = billName.toLowerCase();
        if (name.includes('rent')) return 'rent';
        if (name.includes('deposit')) return 'deposit';
        if (name.includes('utility') || name.includes('water') || name.includes('electric')) return 'utility';
        if (name.includes('maintenance') || name.includes('repair')) return 'maintenance';
        return 'other';
    }

    // Render payment table
    function renderPaymentTable(payments) {
        const tableBody = document.getElementById('paymentTableBody');
        const noResults = document.getElementById('noResults');
        const loadingSpinner = document.getElementById('loadingSpinner');
        
        loadingSpinner.classList.add('hidden');
        
        if (!payments || payments.length === 0) {
            tableBody.innerHTML = '';
            noResults.classList.remove('hidden');
            return;
        }
        
        noResults.classList.add('hidden');
        
        tableBody.innerHTML = payments.map(payment => {
            // For deposit payments, transaction_type might be null, so determine from bill name
            const transactionType = payment.transaction_type || getTransactionType(payment.billing?.bill_name);
            const invoiceNo = `INV-${payment.payment_id?.toString().padStart(4, '0') || '0000'}`;
            const description = payment.billing?.bill_name || `Payment - ${transactionType}`;
            const paymentDate = payment.payment_date;
            const amount = payment.amount_paid || 0;
            const status = payment.billing?.status ? payment.billing.status.toLowerCase() : 'paid';
            
            return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${invoiceNo}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">${description}</div>
                        ${payment.reference_no ? `<div class="text-xs text-gray-500">Ref: ${payment.reference_no}</div>` : ''}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${formatDate(paymentDate)}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getTypeBadge(transactionType)}">
                            ${transactionType.charAt(0).toUpperCase() + transactionType.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${formatCurrency(amount)}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusBadge(status)}">
                            ${status.charAt(0).toUpperCase() + status.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="viewInvoice(${payment.payment_id})" class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                        <button onclick="downloadInvoice(${payment.payment_id})" class="text-green-600 hover:text-green-900">Download</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Update statistics
    function updateStatistics(payments, billings) {
        const totalPayments = payments ? payments.length : 0;
        const totalAmount = payments ? payments.reduce((sum, payment) => sum + parseFloat(payment.amount_paid || 0), 0) : 0;
        
        // Calculate this month's payments
        const now = new Date();
        const thisMonthPayments = payments ? payments.filter(payment => {
            try {
                const paymentDate = new Date(payment.payment_date);
                return paymentDate.getMonth() === now.getMonth() && 
                       paymentDate.getFullYear() === now.getFullYear();
            } catch (e) {
                return false;
            }
        }) : [];
        const thisMonth = thisMonthPayments.reduce((sum, payment) => sum + parseFloat(payment.amount_paid || 0), 0);
        
        // Count pending billings
        const pendingPayments = billings ? billings.filter(billing => 
            billing.status && billing.status.toLowerCase() === 'pending'
        ).length : 0;

        document.getElementById('totalPayments').textContent = totalPayments;
        document.getElementById('totalAmount').textContent = formatCurrency(totalAmount);
        document.getElementById('thisMonth').textContent = formatCurrency(thisMonth);
        document.getElementById('pendingPayments').textContent = pendingPayments;
    }

    // Make API request with proper headers
    async function makeApiRequest(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            credentials: 'same-origin'
        };

        const mergedOptions = { ...defaultOptions, ...options };
        
        try {
            const response = await fetch(url, mergedOptions);
            
            if (!response.ok) {
                // Try to get error message from response
                let errorMessage = `HTTP error! status: ${response.status}`;
                try {
                    const errorData = await response.json();
                    errorMessage = errorData.message || errorData.error || errorMessage;
                } catch (e) {
                    // If response is not JSON, use status text
                    errorMessage = response.statusText || errorMessage;
                }
                throw new Error(errorMessage);
            }
            
            return await response.json();
        } catch (error) {
            console.error('API request failed:', error);
            throw error;
        }
    }

    // Load payments data
    async function loadPayments() {
        const loadingSpinner = document.getElementById('loadingSpinner');
        const tableBody = document.getElementById('paymentTableBody');
        
        loadingSpinner.classList.remove('hidden');
        tableBody.innerHTML = '';

        try {
            console.log('Loading payments...');
            
            // Use the tenant-specific API routes
            const paymentsData = await makeApiRequest('/tenants/api/payments/history');
            
            // Check if there's an error in the response
            if (paymentsData.error) {
                throw new Error(paymentsData.message || paymentsData.error);
            }

            // Fetch billings data for pending counts
            const billingsData = await makeApiRequest('/tenants/api/billings');

            // Check if there's an error in the billings response
            if (billingsData.error) {
                throw new Error(billingsData.message || billingsData.error);
            }

            renderPaymentTable(paymentsData.payments || []);
            updateStatistics(paymentsData.payments || [], billingsData.billings || []);
            
        } catch (error) {
            console.error('Error loading payments:', error);
            document.getElementById('paymentTableBody').innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-red-600">
                        Error loading payments: ${error.message}
                    </td>
                </tr>
            `;
        } finally {
            loadingSpinner.classList.add('hidden');
        }
    }

    // Filter payments
    async function filterPayments() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const typeFilter = document.getElementById('typeFilter').value;
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;

        const loadingSpinner = document.getElementById('loadingSpinner');
        loadingSpinner.classList.remove('hidden');

        try {
            let url = '/tenants/api/payments/history?';
            const params = new URLSearchParams();
            
            if (searchTerm) params.append('search', searchTerm);
            if (statusFilter) params.append('status', statusFilter);
            if (typeFilter) params.append('type', typeFilter);
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);
            
            const data = await makeApiRequest(url + params.toString());
            
            // Check if there's an error in the response
            if (data.error) {
                throw new Error(data.message || data.error);
            }
            
            renderPaymentTable(data.payments || []);
            updateStatistics(data.payments || [], data.billings || []);
            
        } catch (error) {
            console.error('Error filtering payments:', error);
            alert('Error filtering payments: ' + error.message);
        } finally {
            loadingSpinner.classList.add('hidden');
        }
    }

    // View invoice with actual data
    async function viewInvoice(paymentId) {
        try {
            const data = await makeApiRequest(`/tenants/api/payments/${paymentId}/invoice-details`);
            
            if (data.error) {
                throw new Error(data.message || data.error);
            }
            
            showInvoiceModal(data);
        } catch (error) {
            console.error('Error loading invoice:', error);
            alert('Error loading invoice details: ' + error.message);
        }
    }

    // Show invoice modal with actual data
    function showInvoiceModal(data) {
        const payment = data.payment;
        
        document.getElementById('invoiceNumber').textContent = data.invoice_no;
        
        const invoiceContent = document.getElementById('invoiceContent');
        invoiceContent.innerHTML = `
            <div class="bg-white p-6">
                <!-- Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">INVOICE</h1>
                        <p class="text-gray-600">${data.invoice_no}</p>
                    </div>
                    <div class="text-right">
                        <h2 class="text-lg font-semibold">SmartRent</h2>
                        <p class="text-gray-600">123 Business Ave</p>
                        <p class="text-gray-600">Manila, Philippines</p>
                    </div>
                </div>

                <!-- Bill To -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Bill To</h3>
                        <p class="font-medium">${data.user.name}</p>
                        <p class="text-gray-600">${data.user.email}</p>
                        ${payment.lease && payment.lease.property ? 
                            `<p class="text-gray-600">${payment.lease.property.property_name}</p>` : ''}
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Invoice Details</h3>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <span class="text-gray-600">Invoice Date:</span>
                            <span>${data.invoice_date}</span>
                            <span class="text-gray-600">Payment Date:</span>
                            <span>${formatDate(payment.payment_date)}</span>
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="mb-6">
                    <table class="w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="text-left p-3 border border-gray-200 font-semibold">Description</th>
                                <th class="text-right p-3 border border-gray-200 font-semibold">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-3 border border-gray-200">
                                    ${payment.billing ? payment.billing.bill_name : `Payment - ${payment.transaction_type || 'deposit'}`}
                                    ${payment.billing?.bill_period ? `<br><small class="text-gray-500">Period: ${payment.billing.bill_period}</small>` : ''}
                                </td>
                                <td class="p-3 border border-gray-200 text-right">${formatCurrency(payment.amount_paid)}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td class="p-3 border border-gray-200 text-right font-semibold">Total</td>
                                <td class="p-3 border border-gray-200 text-right font-semibold">${formatCurrency(payment.amount_paid)}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Payment Details -->
                <div class="bg-green-50 p-4 rounded-lg mb-6">
                    <h4 class="font-semibold text-green-800 mb-2">Payment Details</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-green-600">Payment Method:</span>
                            <span class="ml-2">${payment.payment_method}</span>
                        </div>
                        <div>
                            <span class="text-green-600">Reference No:</span>
                            <span class="ml-2">${payment.reference_no}</span>
                        </div>
                        <div>
                            <span class="text-green-600">Transaction Type:</span>
                            <span class="ml-2">${payment.transaction_type || 'deposit'}</span>
                        </div>
                        <div>
                            <span class="text-green-600">Status:</span>
                            <span class="ml-2 font-semibold">PAID</span>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="text-center text-gray-500 text-sm">
                    <p>Thank you for your business!</p>
                    <p>For questions, please contact support@smartrent.com</p>
                </div>
            </div>
        `;

        document.getElementById('invoiceModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Download individual invoice PDF
    async function downloadInvoice(paymentId) {
        try {
            // Show loading state
            const originalText = event.target.innerHTML;
            event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Downloading...';
            event.target.disabled = true;

            // Trigger PDF download
            const response = await fetch(`/tenants/api/payments/${paymentId}/invoice-pdf`, {
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            
            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = `invoice-${paymentId}.pdf`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            } else {
                const error = await response.text();
                alert('Error generating PDF: ' + error);
            }
        } catch (error) {
            console.error('Error downloading invoice:', error);
            alert('Error downloading invoice PDF: ' + error.message);
        } finally {
            // Reset button state
            event.target.innerHTML = originalText;
            event.target.disabled = false;
        }
    }

    // Export to PDF report
    async function exportToPDF() {
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        
        try {
            // Show loading state
            const button = document.getElementById('exportPdfBtn');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
            button.disabled = true;

            // Build URL with parameters
            let url = '/tenants/payments/export-report-pdf?';
            const params = new URLSearchParams();
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);
            
            // Open in new tab for PDF download
            window.open(url + params.toString(), '_blank');
            
        } catch (error) {
            console.error('Error exporting PDF:', error);
            alert('Error generating PDF report: ' + error.message);
        } finally {
            // Reset button state
            const button = document.getElementById('exportPdfBtn');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Set default date range (last 3 months)
        const today = new Date();
        const threeMonthsAgo = new Date();
        threeMonthsAgo.setMonth(today.getMonth() - 3);
        
        document.getElementById('dateFrom').value = threeMonthsAgo.toISOString().split('T')[0];
        document.getElementById('dateTo').value = today.toISOString().split('T')[0];

        // Load initial data
        loadPayments();

        // Search and filter events
        document.getElementById('searchInput').addEventListener('input', filterPayments);
        document.getElementById('statusFilter').addEventListener('change', filterPayments);
        document.getElementById('typeFilter').addEventListener('change', filterPayments);
        document.getElementById('filterBtn').addEventListener('click', filterPayments);
        document.getElementById('exportPdfBtn').addEventListener('click', exportToPDF);

        // Invoice modal events
        document.getElementById('closeInvoiceModalBtn').addEventListener('click', function() {
            document.getElementById('invoiceModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        });

        document.getElementById('printInvoiceBtn').addEventListener('click', function() {
            window.print();
        });

        document.getElementById('downloadInvoiceBtn').addEventListener('click', function() {
            const invoiceNo = document.getElementById('invoiceNumber').textContent;
            const paymentId = invoiceNo.split('-')[1]; // Extract payment ID from invoice number
            downloadInvoice(paymentId);
        });

        // Close modal when clicking outside
        document.getElementById('invoiceModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    });



    // Make API request with proper headers and error handling
async function makeApiRequest(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        credentials: 'same-origin' // This is important for sending cookies
    };

    const mergedOptions = { ...defaultOptions, ...options };
    
    try {
        console.log('Making API request to:', url);
        const response = await fetch(url, mergedOptions);
        
        // Handle authentication redirects
        if (response.status === 401 || response.status === 419) {
            // Session expired or not authenticated
            window.location.href = '/login';
            throw new Error('Authentication required. Redirecting to login...');
        }
        
        if (!response.ok) {
            // Try to get error message from response
            let errorMessage = `HTTP error! status: ${response.status}`;
            try {
                const errorData = await response.json();
                errorMessage = errorData.message || errorData.error || errorMessage;
                // Include debug info if available
                if (errorData.debug) {
                    errorMessage += ` (Debug: ${JSON.stringify(errorData.debug)})`;
                }
            } catch (e) {
                // If response is not JSON, use status text
                errorMessage = response.statusText || errorMessage;
            }
            throw new Error(errorMessage);
        }
        
        return await response.json();
    } catch (error) {
        console.error('API request failed:', error);
        // If it's a network error or CORS issue
        if (error.name === 'TypeError' || error.name === 'NetworkError') {
            throw new Error('Network error. Please check your connection and try again.');
        }
        throw error;
    }
}
</script>
@endpush