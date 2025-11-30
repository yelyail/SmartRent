@extends('layouts.landlord')

@section('title', 'User Management - SmartRent')
@section('page-description', 'Manage tenant information, leases, and communications.')

@section('content')
<div class="p-8 pb-4">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Tenants -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Tenants</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalTenants }}</p>
                </div>
            </div>
        </div>

        <!-- Active -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $activeTenants }}</p>
                </div>
            </div>
        </div>

        <!-- Late Payments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Late Payments</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $latePayments }}</p>
                </div>
            </div>
        </div>

        <!-- Expiring Soon -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-times text-orange-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Expiring Soon</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $expiringSoon }}</p>
                </div>                           
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div class="flex-1 max-w-md w-full">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Search tenants..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <div class="flex space-x-4 w-full md:w-auto">
                <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full md:w-auto">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="terminated">Terminated</option>
                </select>
                <button id="clearFilters" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Tenants Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Tenant</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Property</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Unit</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Lease Period</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Rent</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Status</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Deposit</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="tenantsTableBody" class="divide-y divide-gray-200">
                    @forelse($leases as $lease)
                    <tr class="hover:bg-gray-50 transition-colors" 
                        data-status="{{ $lease->status }}" 
                        data-name="{{ $lease->user->first_name }} {{ $lease->user->last_name }}" 
                        data-property="{{ $lease->property->property_name }}">
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $lease->user->first_name }} {{ $lease->user->last_name }}
                                    </p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span class="flex items-center space-x-1">
                                            <i class="fas fa-envelope text-xs"></i>
                                            <span>{{ $lease->user->email }}</span>
                                        </span>
                                        @if($lease->user->phone_num)
                                        <span class="flex items-center space-x-1">
                                            <i class="fas fa-phone text-xs"></i>
                                            <span>{{ $lease->user->phone_num }}</span>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-home text-gray-400"></i>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $lease->property->property_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $lease->property->property_address }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            @if($lease->unit)
                                <p class="font-medium text-gray-900">{{ $lease->unit->unit_name ?? 'Unit ' . $lease->unit->unit_num }}</p>
                                <p class="text-sm text-gray-500">{{ $lease->unit->unit_type }}</p>
                            @else
                                <p class="text-sm text-gray-500">No unit assigned</p>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar text-gray-400"></i>
                                <div>
                                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($lease->start_date)->format('M d, Y') }}</p>
                                    <p class="text-sm text-gray-500">to {{ \Carbon\Carbon::parse($lease->end_date)->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-medium text-gray-900">₱{{ number_format($lease->rent_amount, 2) }}</p>
                            <p class="text-sm text-gray-500">per month</p>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $lease->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($lease->status === 'approved' ? 'bg-blue-100 text-blue-800' : 
                                   ($lease->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800')) }}">
                                {{ ucfirst($lease->status) }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-2">
                                @if($lease->deposit_paid)
                                <i class="fas fa-check-circle text-green-500 text-lg" title="Deposit Paid"></i>
                                @else
                                <i class="fas fa-clock text-yellow-500 text-lg" title="Deposit Pending"></i>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-2">
                                <!-- View Details Icon -->
                                <button onclick="openTenantDetails({{ $lease->user->user_id }})" class="text-blue-600 hover:text-blue-800 transition-colors" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <!-- Approve Lease Icon (only show for pending leases) -->
                                @if($lease->status === 'pending')
                                <button onclick="approveLease({{ $lease->lease_id }})" class="text-green-600 hover:text-green-800 transition-colors" title="Approve Lease">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                                @endif
                                
                                <!-- Terminate Lease Icon -->
                                <button onclick="terminateLease({{ $lease->lease_id }})" class="text-red-600 hover:text-red-800 transition-colors" title="Terminate Lease">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 px-6 text-center">
                            <div class="text-gray-400 text-4xl mb-2">👥</div>
                            <h3 class="text-lg font-semibold text-gray-600 mb-2">No Tenants Found</h3>
                            <p class="text-gray-500 mb-4">You don't have any tenants yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tenant Details Modal -->
<div id="tenantDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900" id="tenantModalName">Tenant Details</h2>
                    <p class="text-sm text-gray-500">Complete tenant information and documents</p>
                </div>
            </div>
            <button onclick="closeTenantDetails()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                            <i class="fas fa-user-circle text-blue-600"></i>
                            <span>Personal Information</span>
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Full Name:</span>
                                <span class="text-sm font-medium text-gray-900" id="tenantFullName"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Email:</span>
                                <span class="text-sm font-medium text-gray-900" id="tenantEmail"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Phone:</span>
                                <span class="text-sm font-medium text-gray-900" id="tenantPhone"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Member Since:</span>
                                <span class="text-sm font-medium text-gray-900" id="tenantSince"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Lease Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                            <i class="fas fa-file-contract text-green-600"></i>
                            <span>Lease Information</span>
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Property:</span>
                                <span class="text-sm font-medium text-gray-900" id="leaseProperty"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Unit:</span>
                                <span class="text-sm font-medium text-gray-900" id="leaseUnit"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Monthly Rent:</span>
                                <span class="text-sm font-medium text-gray-900" id="leaseRent"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Lease Period:</span>
                                <span class="text-sm font-medium text-gray-900" id="leasePeriod"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Status:</span>
                                <span class="text-sm font-medium" id="leaseStatus"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KYC Documents -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                            <i class="fas fa-id-card text-purple-600"></i>
                            <span>KYC Documents</span>
                            <span id="kycStatusBadge" class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"></span>
                        </h3>
                        
                        <!-- ID Document -->
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Government ID</h4>
                            <div id="idDocument" class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                <div class="text-gray-400 text-4xl mb-2">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <p class="text-sm text-gray-500">No ID document uploaded</p>
                            </div>
                        </div>

                        <!-- Proof of Income -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Proof of Income</h4>
                            <div id="incomeDocument" class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                <div class="text-gray-400 text-4xl mb-2">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <p class="text-sm text-gray-500">No proof of income uploaded</p>
                            </div>
                        </div>

                        <!-- KYC Actions -->
                        <div id="kycActions" class="mt-4 flex space-x-2 hidden">
                            <button onclick="approveKYC()" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                                <i class="fas fa-check text-sm"></i>
                                <span>Approve KYC</span>
                            </button>
                            <button onclick="rejectKYC()" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                                <i class="fas fa-times text-sm"></i>
                                <span>Reject KYC</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end space-x-4 p-6 border-t border-gray-200">
            <button onclick="closeTenantDetails()" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Close
            </button>
            <button onclick="downloadAllDocuments()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center space-x-2">
                <i class="fas fa-download"></i>
                <span>Download All Documents</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentTenantId = null;
let currentKycData = null;

// Open tenant details modal - Alternative fix
// Open tenant details modal with better debugging
function openTenantDetails(userId) {
    currentTenantId = userId;
    
    fetch(`/landlord/tenants/${userId}/details`)
        .then(response => {
            return response.json();
        })
        .then(data => {
            
            if (data.success) {
                currentKycData = data.kyc;
                populateTenantDetails(data.tenant, data.kyc);
            } else {
                alert('Failed to load tenant details: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('An error occurred while loading tenant details.');
        });
}

// Updated populateTenantDetails function to accept kyc parameter
function populateTenantDetails(data, kyc) {
    // Personal Information
    document.getElementById('tenantModalName').textContent = `${data.user.first_name} ${data.user.last_name}`;
    document.getElementById('tenantFullName').textContent = `${data.user.first_name} ${data.user.last_name}`;
    document.getElementById('tenantEmail').textContent = data.user.email;
    document.getElementById('tenantPhone').textContent = data.user.phone_num || 'Not provided';
    document.getElementById('tenantSince').textContent = new Date(data.user.created_at).toLocaleDateString();
    
    // Lease Information
    document.getElementById('leaseProperty').textContent = data.property.property_name;
    document.getElementById('leaseUnit').textContent = data.unit ? (data.unit.unit_name || `Unit ${data.unit.unit_num}`) : 'Not assigned';
    document.getElementById('leaseRent').textContent = `₱${parseFloat(data.rent_amount).toLocaleString()}`;
    document.getElementById('leasePeriod').textContent = `${new Date(data.start_date).toLocaleDateString()} - ${new Date(data.end_date).toLocaleDateString()}`;
    
    const statusElement = document.getElementById('leaseStatus');
    statusElement.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
    statusElement.className = `text-sm font-medium ${
        data.status === 'active' ? 'text-green-600' :
        data.status === 'approved' ? 'text-blue-600' :
        data.status === 'pending' ? 'text-yellow-600' :
        'text-red-600'
    }`;
    
    // KYC Documents - Use the passed kyc parameter
    populateKYCDocuments(kyc);
    
    // Show modal
    document.getElementById('tenantDetailsModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function populateKYCDocuments(kyc) {
    const kycStatusBadge = document.getElementById('kycStatusBadge');
    const kycActions = document.getElementById('kycActions');
    
    // Check if elements exist
    if (!kycStatusBadge) {
        console.error('KYC Status Badge element not found!');
        return;
    }
    if (!kycActions) {
        console.error('KYC Actions element not found!');
    }
    
    // Check if kyc exists and has data
    const hasKycData = kyc && (kyc.doc_path || kyc.proof_of_income || kyc.status);
    
    if (hasKycData) {
        // KYC Status
        const status = kyc.status || 'pending';
        
        kycStatusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        kycStatusBadge.className = `ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
            status === 'approved' ? 'bg-green-100 text-green-800' :
            status === 'rejected' ? 'bg-red-100 text-red-800' :
            'bg-yellow-100 text-yellow-800'
        }`;
        
        // Make sure the badge is visible
        kycStatusBadge.style.display = 'inline-flex';
        
        // ID Document
        if (kyc.doc_path) {
            const idDocUrl = `/storage/${kyc.doc_path}`;
            const fileName = kyc.doc_path.split('/').pop() || 'id_document';
            
            document.getElementById('idDocument').innerHTML = `
                <div class="text-center">
                    <i class="fas fa-id-card text-green-500 text-3xl mb-2"></i>
                    <p class="text-sm font-medium text-gray-900 mb-1">ID Document</p>
                    <p class="text-xs text-gray-500 mb-3 truncate">${fileName}</p>
                    <div class="flex justify-center space-x-2">
                        <button onclick="viewDocument('${idDocUrl}')" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-3 py-1 border border-blue-600 rounded hover:bg-blue-50 transition-colors">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button onclick="downloadDocument('${idDocUrl}', '${fileName}')" class="text-green-600 hover:text-green-800 text-sm font-medium px-3 py-1 border border-green-600 rounded hover:bg-green-50 transition-colors">
                            <i class="fas fa-download mr-1"></i>Download
                        </button>
                    </div>
                </div>
            `;
        } else {
            document.getElementById('idDocument').innerHTML = `
                <div class="text-center">
                    <div class="text-gray-400 text-4xl mb-2">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <p class="text-sm text-gray-500">No ID document uploaded</p>
                </div>
            `;
        }
        
        // Proof of Income
        if (kyc.proof_of_income) {
            const incomeDocUrl = `/storage/${kyc.proof_of_income}`;
            const fileName = kyc.proof_of_income.split('/').pop() || 'proof_of_income';
            
            document.getElementById('incomeDocument').innerHTML = `
                <div class="text-center">
                    <i class="fas fa-file-invoice-dollar text-green-500 text-3xl mb-2"></i>
                    <p class="text-sm font-medium text-gray-900 mb-1">Proof of Income</p>
                    <p class="text-xs text-gray-500 mb-3 truncate">${fileName}</p>
                    <div class="flex justify-center space-x-2">
                        <button onclick="viewDocument('${incomeDocUrl}')" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-3 py-1 border border-blue-600 rounded hover:bg-blue-50 transition-colors">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button onclick="downloadDocument('${incomeDocUrl}', '${fileName}')" class="text-green-600 hover:text-green-800 text-sm font-medium px-3 py-1 border border-green-600 rounded hover:bg-green-50 transition-colors">
                            <i class="fas fa-download mr-1"></i>Download
                        </button>
                    </div>
                </div>
            `;
        } else {
            document.getElementById('incomeDocument').innerHTML = `
                <div class="text-center">
                    <div class="text-gray-400 text-4xl mb-2">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <p class="text-sm text-gray-500">No proof of income uploaded</p>
                </div>
            `;
        }
        
        // Show KYC actions if pending
        if (status === 'pending') {
            kycActions.classList.remove('hidden');
        } else {
            kycActions.classList.add('hidden');
        }
    } else {
        // No KYC data found
        kycStatusBadge.textContent = 'Not Submitted';
        kycStatusBadge.className = 'ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
        kycStatusBadge.style.display = 'inline-flex';
        kycActions.classList.add('hidden');
        
        // Reset document sections
        document.getElementById('idDocument').innerHTML = `
            <div class="text-center">
                <div class="text-gray-400 text-4xl mb-2">
                    <i class="fas fa-id-card"></i>
                </div>
                <p class="text-sm text-gray-500">No ID document uploaded</p>
            </div>
        `;
        
        document.getElementById('incomeDocument').innerHTML = `
            <div class="text-center">
                <div class="text-gray-400 text-4xl mb-2">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <p class="text-sm text-gray-500">No proof of income uploaded</p>
            </div>
        `;
    }
}

// Enhanced document viewing and downloading
function viewDocument(url) {
    // For PDF files, open in new tab
    if (url.toLowerCase().endsWith('.pdf')) {
        window.open(url, '_blank');
    } else {
        // For images, open in a modal or new tab
        window.open(url, '_blank');
    }
}

function downloadDocument(url, filename) {
    const link = document.createElement('a');
    link.href = url;
    link.target = '_blank'; // Open in new tab for better UX
    
    // Ensure filename has proper extension
    if (!filename.toLowerCase().includes('.')) {
        if (url.toLowerCase().endsWith('.pdf')) {
            filename += '.pdf';
        } else if (url.toLowerCase().endsWith('.jpg') || url.toLowerCase().endsWith('.jpeg')) {
            filename += '.jpg';
        } else if (url.toLowerCase().endsWith('.png')) {
            filename += '.png';
        }
    }
    
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function downloadAllDocuments() {
    if (currentKycData) {
        let documents = [];
        
        if (currentKycData.doc_path) {
            const idDocUrl = `/storage/${currentKycData.doc_path}`;
            const fileName = currentKycData.doc_path.split('/').pop() || 'id_document';
            documents.push({
                url: idDocUrl,
                filename: fileName
            });
        }
        
        if (currentKycData.proof_of_income) {
            const incomeDocUrl = `/storage/${currentKycData.proof_of_income}`;
            const fileName = currentKycData.proof_of_income.split('/').pop() || 'proof_of_income';
            documents.push({
                url: incomeDocUrl,
                filename: fileName
            });
        }
        
        if (documents.length > 0) {
            // Download documents with delay to avoid browser blocking multiple downloads
            documents.forEach((doc, index) => {
                setTimeout(() => {
                    downloadDocument(doc.url, doc.filename);
                }, index * 1000);
            });
            
            if (documents.length === 1) {
                alert('Downloading document...');
            } else {
                alert(`Downloading ${documents.length} documents...`);
            }
        } else {
            alert('No documents available to download.');
        }
    } else {
        alert('No KYC data available.');
    }
}

// Close tenant details modal
function closeTenantDetails() {
    document.getElementById('tenantDetailsModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentTenantId = null;
    currentKycData = null;
}

// KYC Approval/Rejection
function approveKYC() {
    if (confirm('Are you sure you want to approve this KYC?')) {
        updateKYCStatus('approved');
    }
}

function rejectKYC() {
    if (confirm('Are you sure you want to reject this KYC?')) {
        updateKYCStatus('rejected');
    }
}

function updateKYCStatus(status) {
    fetch(`/landlord/kyc/${currentKycData.kyc_id}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`KYC ${status} successfully!`);
            closeTenantDetails();
        } else {
            alert('Failed to update KYC status: ' + data.message);
        }
    })
    .catch(error => {
        alert('An error occurred while updating KYC status.');
    });
}

// Search and filter functionality
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const clearFilters = document.getElementById('clearFilters');
const tenantsTableBody = document.getElementById('tenantsTableBody');
const tenantRows = tenantsTableBody.querySelectorAll('tr[data-status]');

function filterTenants() {
    const searchTerm = searchInput.value.toLowerCase();
    const statusValue = statusFilter.value;
    
    tenantRows.forEach(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        const property = row.getAttribute('data-property').toLowerCase();
        const status = row.getAttribute('data-status');
        
        const matchesSearch = name.includes(searchTerm) || property.includes(searchTerm);
        const matchesStatus = statusValue === 'all' || status === statusValue;
        
        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

searchInput.addEventListener('input', filterTenants);
statusFilter.addEventListener('change', filterTenants);

clearFilters.addEventListener('click', () => {
    searchInput.value = '';
    statusFilter.value = 'all';
    filterTenants();
});

// Lease management functions
function approveLease(leaseId) {
    if (confirm('Are you sure you want to approve this lease?')) {
        fetch(`/landlord/leases/${leaseId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to approve lease: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred while approving the lease.');
        });
    }
}

function terminateLease(leaseId) {
    if (confirm('Are you sure you want to terminate this lease? This action cannot be undone.')) {
        fetch(`/landlord/leases/${leaseId}/terminate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to terminate lease: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred while terminating the lease.');
        });
    }
}
</script>
@endpush