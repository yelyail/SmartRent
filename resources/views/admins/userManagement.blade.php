@extends('layouts.admin')

@section('title', 'User Management - SmartRent')
@section('page-title', 'User Management')
@section('page-description', 'Manage tenant information, leases, and communications.')

@section('header-actions')
    <button id="kycApprovalBtn" class="bg-orange-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-orange-700 transition-colors flex items-center space-x-2">    
        <i class="fas fa-clipboard-check text-sm"></i>
        <span>KYC Approvals</span>
        <span id="pendingKycCount" class="bg-red-500 text-white text-xs rounded-full px-2 py-1">{{ $stats['pending_kyc'] ?? 0 }}</span>
    </button>
    <button id="addUserBtn" class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition-colors flex items-center space-x-2">
        <i class="fas fa-user-plus text-sm"></i>
        <span>New User</span>
    </button>
@endsection

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
                    <p id="totalTenants" class="text-3xl font-bold text-gray-900">{{ $stats['total_tenants'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Active Users (All Roles) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active Users</p>
                    <p id="activeUsers" class="text-3xl font-bold text-gray-900">{{ $stats['active_users'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Landlords</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_landlords'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <!-- Pending KYC -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-yellow-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending KYC</p>
                    <p id="pendingKyc" class="text-3xl font-bold text-gray-900">{{ $stats['pending_kyc'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div class="flex-1 max-w-md w-full">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-3/4 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Search users..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <div class="flex space-x-4 w-full md:w-auto">
                <select id="roleFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full md:w-auto">
                    <option value="all">All Roles</option>
                    <option value="tenants">Tenants</option>
                    <option value="landlord">Landlords</option>
                    <option value="staff">Staff</option>
                    <option value="admin">Admins</option>
                </select>
                <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full md:w-auto">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="banned">Banned</option>
                </select>
                <button id="clearFilters" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">User</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Role</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Property/Unit</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Contact</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Status</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">KYC Status</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody" class="divide-y divide-gray-200">
                    <!-- Initial users from server-side -->
                    @foreach($users as $user)
                    @php
                        $latestKyc = $user->kycDocuments->first();
                        $kycStatus = $latestKyc ? $latestKyc->status : 'none';
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors" data-user-id="{{ $user->user_id }}">
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($user->role->value === 'tenants') bg-blue-100 text-blue-800
                                @elseif($user->role->value === 'landlords') bg-purple-100 text-purple-800
                                @elseif($user->role->value === 'staff') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif capitalize">
                                {{ $user->role->value }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-gray-400 text-sm">N/A</p>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm text-gray-500">
                                <div class="flex items-center space-x-1 mb-1">
                                    <i class="fas fa-phone text-xs"></i>
                                    <span>{{ $user->phone_num ?: 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($user->status === 'active') bg-green-100 text-green-800
                                @elseif($user->status === 'inactive') bg-yellow-100 text-yellow-800
                                @elseif($user->status === 'banned') bg-red-100 text-red-800
                                @else bg-red-100 text-red-800
                                @endif capitalize">
                                {{ $user->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($kycStatus === 'approved') bg-green-100 text-green-800
                                @elseif($kycStatus === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($kycStatus === 'reject') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif capitalize">
                                {{ $kycStatus }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-2">
                                <!-- Edit Icon -->
                                <button onclick="editUser('{{ $user->user_id }}')" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <!-- Status Toggle Icon -->
                                <button onclick="handleStatusUpdate('{{ $user->user_id }}', '{{ $user->status }}')" 
                                        class="text-green-600 hover:text-green-800 transition-colors" 
                                        title="{{ $user->status === 'active' ? 'Banned' : 'Activate' }}">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                
                                <!-- KYC View Icon (only for tenants/landlords) -->
                                @if($user->role->value === 'tenants' || $user->role->value === 'landlord')
                                <button onclick="viewKycDetails('{{ $user->user_id }}')" class="text-purple-600 hover:text-purple-800 transition-colors" title="View KYC Details">
                                    <i class="fas fa-id-card"></i>
                                </button>
                                @endif
                                
                                <!-- Archive Icon -->
                                <button onclick="archiveUser('{{ $user->user_id }}')" class="text-red-600 hover:text-red-800 transition-colors" title="Archive User">
                                    <i class="fas fa-archive"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                </div>
                <div class="flex space-x-2">
                    @if($users->onFirstPage())
                    <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">Previous</span>
                    @else
                    <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1 text-blue-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Previous</a>
                    @endif

                    @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1 text-blue-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Next</a>
                    @else
                    <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-plus text-green-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Add New User</h2>
                    <p class="text-sm text-gray-500">Create a new user account</p>
                </div>
            </div>
            <button id="closeAddUserModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="addUserForm" class="p-6">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <!-- Personal Information Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                        <i class="fas fa-user-circle text-blue-600"></i>
                        <span>Personal Information</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                   placeholder="Enter first name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                            <input type="text" name="middle_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                   placeholder="Enter middle name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                   placeholder="Enter last name">
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                        <i class="fas fa-address-book text-green-600"></i>
                        <span>Contact Information</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                   placeholder="user@example.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone_num" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                   placeholder="+1 (555) 000-0000">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Address <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="address" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="Enter complete address">
                    </div>
                </div>

                <!-- Account Information Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                        <i class="fas fa-user-cog text-purple-600"></i>
                        <span>Account Information</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select name="role" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                <option value="">Select User Role</option>
                                <option value="tenants">Tenant</option>
                                <option value="landlords">Landlord</option>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required 
                                       class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                       placeholder="Enter password"
                                       minlength="12">
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                                        onclick="togglePasswordVisibility('password', 'passwordToggle')">
                                    <i id="passwordToggle" class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Minimum 12 characters</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" required 
                                       class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                       placeholder="Confirm password"
                                       minlength="12">
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                                        onclick="togglePasswordVisibility('password_confirmation', 'confirmPasswordToggle')">
                                    <i id="confirmPasswordToggle" class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Re-enter your password</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <button type="button" id="cancelAddUser" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center space-x-2">
                    <i class="fas fa-user-plus"></i>
                    <span>Create User</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- KYC Approval Modal -->
<div id="kycModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clipboard-check text-orange-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">KYC Approvals</h2>
                    <p class="text-sm text-gray-500">Review and approve KYC documents</p>
                </div>
            </div>
            <button id="closeKycModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="kycList" class="p-6">
            <!-- KYC documents will be loaded here via AJAX -->
        </div>
    </div>
</div>

<!-- KYC Details Modal -->
<div id="kycDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-id-card text-purple-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">KYC Details</h2>
                    <p class="text-sm text-gray-500">Personal information and uploaded documents</p>
                </div>
            </div>
            <button id="closeKycDetails" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="kycDetailsContent" class="p-6">
            <!-- KYC details will be loaded here via AJAX -->
        </div>
    </div>
</div>

<!-- Document Viewer Modal -->
<div id="documentViewerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900" id="documentViewerTitle">Document Viewer</h2>
                    <p class="text-sm text-gray-500" id="documentViewerSubtitle">View uploaded document</p>
                </div>
            </div>
            <button id="closeDocumentViewer" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-6">
            <div id="documentViewerContent" class="flex justify-center">
                <!-- Document will be displayed here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Password visibility toggle function
    function togglePasswordVisibility(passwordFieldId, toggleIconId) {
        const passwordField = document.getElementById(passwordFieldId);
        const toggleIcon = document.getElementById(toggleIconId);
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        }
    }
    // Modal elements
    const kycModal = document.getElementById('kycModal');
    const addUserModal = document.getElementById('addUserModal');
    const kycDetailsModal = document.getElementById('kycDetailsModal');
    const documentViewerModal = document.getElementById('documentViewerModal');

    // Open modals
    document.getElementById('kycApprovalBtn').addEventListener('click', () => {
        kycModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        loadKycDocuments();
    });

    document.getElementById('addUserBtn').addEventListener('click', () => {
        addUserModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });
    document.getElementById('addUserForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Creating User...</span>';
        submitBtn.disabled = true;
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);
        
        try {
            const response = await fetch('/admin/users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                // Show success message
                alert('✅ ' + result.message);
                closeAddUserModal();
                
                // Use setTimeout to ensure alert is visible before any potential page changes
                setTimeout(() => {
                    // Check if loadUsers is an AJAX function or if it causes page reload
                    if (typeof loadUsers === 'function') {
                        loadUsers(); // Refresh the user table via AJAX
                    } else {
                        // If loadUsers causes page reload, consider removing it
                        window.location.reload(); // Or use a different approach
                    }
                }, 100);
                
            } else {
                // Show validation errors or error message
                if (result.errors) {
                    const errorMessages = Object.values(result.errors).flat().join('\n');
                    alert('❌ ' + errorMessages);
                } else {
                    alert('❌ ' + result.message);
                }
            }
        } catch (error) {
            console.error('Error creating user:', error);
            alert('❌ Error creating user. Please try again.');
        } finally {
            // Reset button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });

    // Close modals
    document.getElementById('closeKycModal').addEventListener('click', closeKycModal);
    document.getElementById('closeAddUserModal').addEventListener('click', closeAddUserModal);
    document.getElementById('closeKycDetails').addEventListener('click', closeKycDetails);
    document.getElementById('cancelAddUser').addEventListener('click', closeAddUserModal);
    document.getElementById('closeDocumentViewer').addEventListener('click', closeDocumentViewer);

    function closeKycModal() {
        kycModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function closeAddUserModal() {
        addUserModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('addUserForm').reset();
    }

    function closeKycDetails() {
        kycDetailsModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function closeDocumentViewer() {
        documentViewerModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modals when clicking outside
    kycModal.addEventListener('click', (e) => {
        if (e.target === kycModal) closeKycModal();
    });
    addUserModal.addEventListener('click', (e) => {
        if (e.target === addUserModal) closeAddUserModal();
    });
    kycDetailsModal.addEventListener('click', (e) => {
        if (e.target === kycDetailsModal) closeKycDetails();
    });
    documentViewerModal.addEventListener('click', (e) => {
        if (e.target === documentViewerModal) closeDocumentViewer();
    });

    // Search and filter functionality
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const clearFilters = document.getElementById('clearFilters');

    // Load users on page load (for AJAX filtering)
    document.addEventListener('DOMContentLoaded', function() {
        // Set up AJAX filtering with debounce
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadUsers();
            }, 100); // 300ms delay
        });
        
        roleFilter.addEventListener('change', loadUsers);
        statusFilter.addEventListener('change', loadUsers);
        
        clearFilters.addEventListener('click', () => {
            searchInput.value = '';
            roleFilter.value = 'all';
            statusFilter.value = 'all';
            loadUsers();
        });
    });

    async function loadUsers() {
        try {
            const search = document.getElementById('searchInput').value;
            const role = document.getElementById('roleFilter').value;
            const status = document.getElementById('statusFilter').value;
            
            const url = `/admin/users?search=${encodeURIComponent(search)}&role=${role}&status=${status}`;
            const response = await fetch(url);
            const result = await response.json();
            
            // This should update the table without page reload
            displayUsers(result.data);
            
        } catch (error) {
            console.error('Error loading users:', error);
        }
    }

    function displayUsers(users) {
        const tbody = document.getElementById('usersTableBody');
        
        if (users.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="py-8 px-6 text-center text-gray-500">
                        <i class="fas fa-users text-4xl mb-2 text-gray-300"></i>
                        <p>No users found</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = users.map(user => `
            <tr class="hover:bg-gray-50 transition-colors" data-user-id="${user.user_id}">
                <td class="py-4 px-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">${user.first_name} ${user.last_name}</p>
                            <p class="text-sm text-gray-500">${user.email}</p>
                        </div>
                    </div>
                </td>
                <td class="py-4 px-6">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        user.role === 'tenants' ? 'bg-blue-100 text-blue-800' :
                        user.role === 'landlord' ? 'bg-purple-100 text-purple-800' :
                        user.role === 'staff' ? 'bg-yellow-100 text-yellow-800' :
                        'bg-red-100 text-red-800'
                    } capitalize">
                        ${user.role}
                    </span>
                </td>
                <td class="py-4 px-6">
                    ${user.property ? `
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-home text-gray-400"></i>
                            <div>
                                <p class="font-medium text-gray-900">${user.property}</p>
                                ${user.unit ? `<p class="text-sm text-gray-500">${user.unit}</p>` : ''}
                            </div>
                        </div>
                    ` : '<p class="text-gray-400 text-sm">N/A</p>'}
                </td>
                <td class="py-4 px-6">
                    <div class="text-sm text-gray-500">
                        <div class="flex items-center space-x-1 mb-1">
                            <i class="fas fa-phone text-xs"></i>
                            <span>${user.phone_num || 'N/A'}</span>
                        </div>
                    </div>
                </td>
                <td class="py-4 px-6">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        user.status === 'active' ? 'bg-green-100 text-green-800' :
                        user.status === 'inactive' ? 'bg-yellow-100 text-yellow-800' :
                        'bg-red-100 text-red-800'
                    } capitalize">
                        ${user.status}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        user.kyc_status === 'approved' ? 'bg-green-100 text-green-800' :
                        user.kyc_status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                        'bg-gray-100 text-gray-800'
                    } capitalize">
                        ${user.kyc_status || 'none'}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center space-x-2">
                        <!-- Edit Icon -->
                        <button onclick="editUser(${user.user_id})" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit User">
                            <i class="fas fa-edit"></i>
                        </button>
                        
                        <!-- Status Toggle Icon -->
                        <button onclick="handleStatusUpdate(${user.user_id}, '${user.status}')" class="text-green-600 hover:text-green-800 transition-colors" title="${user.status === 'active' ? 'Banned' : 'Activate'}">
                            <i class="fas fa-power-off"></i>
                        </button>
                        
                        <!-- KYC View Icon (only for tenants/landlords) -->
                        ${user.role === 'tenants' || user.role === 'landlord' ? `
                        <button onclick="viewKycDetails(${user.user_id})" class="text-purple-600 hover:text-purple-800 transition-colors" title="View KYC Details">
                            <i class="fas fa-id-card"></i>
                        </button>
                        ` : ''}
                        
                        <!-- Archive Icon -->
                        <button onclick="archiveUser(${user.user_id})" class="text-red-600 hover:text-red-800 transition-colors" title="Archive User">
                            <i class="fas fa-archive"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    // Load KYC documents with enhanced document viewing
    async function loadKycDocuments() {
        try {
            const response = await fetch('/admin/kyc/pending');
            const kycDocuments = await response.json();
            displayKycDocuments(kycDocuments);
        } catch (error) {
            console.error('Error loading KYC documents:', error);
        }
    }

    function displayKycDocuments(documents) {
        const container = document.getElementById('kycList');
        if (documents.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-500 py-8">No pending KYC documents.</p>';
            return;
        }

        container.innerHTML = documents.map(doc => `
            <div class="border border-gray-200 rounded-lg p-4 mb-4">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">${doc.user.first_name} ${doc.user.last_name}</h3>
                        <p class="text-sm text-gray-600">${doc.user.email} • ${doc.user.phone_num}</p>
                        <p class="text-sm text-gray-600 mt-2">Document Type: <span class="font-medium">${doc.doc_type}</span></p>
                        <p class="text-sm text-gray-600">Document Name: <span class="font-medium">${doc.doc_name}</span></p>
                        <p class="text-sm text-gray-600">Uploaded: ${new Date(doc.created_at).toLocaleDateString()}</p>
                        
                        <!-- Document Preview Section -->
                        <div class="mt-4">
                            <h4 class="font-medium text-gray-900 mb-2">Uploaded Documents:</h4>
                            <div class="flex space-x-4">
                                <!-- Main Document -->
                                <div class="flex flex-col items-center">
                                    <button onclick="viewDocument('${doc.doc_path}', '${doc.doc_name}', '${doc.doc_type}')" class="bg-blue-100 text-blue-600 p-3 rounded-lg hover:bg-blue-200 transition-colors mb-2">
                                        <i class="fas fa-file-pdf text-xl"></i>
                                    </button>
                                    <span class="text-xs text-gray-600">Main Document</span>
                                </div>
                                
                                <!-- Proof of Income (if exists) -->
                                ${doc.proof_of_income ? `
                                <div class="flex flex-col items-center">
                                    <button onclick="viewDocument('${doc.proof_of_income}', 'Proof of Income', 'Income Proof')" class="bg-green-100 text-green-600 p-3 rounded-lg hover:bg-green-200 transition-colors mb-2">
                                        <i class="fas fa-file-invoice-dollar text-xl"></i>
                                    </button>
                                    <span class="text-xs text-gray-600">Income Proof</span>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-2 ml-4">
                        <button onclick="approveKyc(${doc.kyc_id})" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center space-x-2">
                            <i class="fas fa-check"></i>
                            <span>Approve</span>
                        </button>
                        <button onclick="rejectKyc(${doc.kyc_id})" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center space-x-2">
                            <i class="fas fa-times"></i>
                            <span>Reject</span>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }
    // View KYC Details for a specific user
    async function viewKycDetails(userId) {
        try {
            const response = await fetch(`/admin/users/${userId}/kyc`);
            const data = await response.json();
            
            if (data.success) {
                displayKycDetails(data.user, data.kycDocuments);
            } else {
                alert('Error loading KYC details: ' + data.message);
            }
        } catch (error) {
            console.error('Error loading KYC details:', error);
            alert('Error loading KYC details');
        }
    }

    function displayKycDetails(user, kycDocuments) {
        const content = document.getElementById('kycDetailsContent');
        
        content.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                        <i class="fas fa-user text-blue-600"></i>
                        <span>Personal Information</span>
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Full Name</label>
                            <p class="text-gray-900">${user.first_name} ${user.last_name}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Email</label>
                            <p class="text-gray-900">${user.email}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Phone</label>
                            <p class="text-gray-900">${user.phone_num || 'N/A'}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Address</label>
                            <p class="text-gray-900">${user.address || 'N/A'}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Role</label>
                            <p class="text-gray-900 capitalize">${user.role}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                user.status === 'active' ? 'bg-green-100 text-green-800' :
                                user.status === 'inactive' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-red-100 text-red-800'
                            } capitalize">
                                ${user.status}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- KYC Documents -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                        <i class="fas fa-file-alt text-green-600"></i>
                        <span>Uploaded Documents</span>
                    </h3>
                    ${kycDocuments.length > 0 ? `
                        <div class="space-y-4">
                            ${kycDocuments.map(doc => `
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">${doc.doc_type}</h4>
                                            <p class="text-sm text-gray-600">${doc.doc_name}</p>
                                            <p class="text-sm text-gray-600">Status: 
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${
                                                    doc.status === 'approved' ? 'bg-green-100 text-green-800' :
                                                    doc.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                    'bg-red-100 text-red-800'
                                                } capitalize">
                                                    ${doc.status}
                                                </span>
                                            </p>
                                            <p class="text-sm text-gray-600">Uploaded: ${new Date(doc.created_at).toLocaleDateString()}</p>
                                        </div>
                                        <div class="flex space-x-2 ml-4">
                                            <button onclick="viewDocument('${doc.doc_path}', '${doc.doc_name}', '${doc.doc_type}')" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition-colors text-sm">
                                                View
                                            </button>
                                            ${doc.proof_of_income ? `
                                            <button onclick="viewDocument('${doc.proof_of_income}', 'Proof of Income', 'Income Proof')" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition-colors text-sm">
                                                Income Proof
                                            </button>
                                            ` : ''}
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    ` : `
                        <p class="text-gray-500 text-center py-4">No KYC documents uploaded yet.</p>
                    `}
                </div>
            </div>
        `;
        
        kycDetailsModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Document viewing functionality
    function viewDocument(docPath, docName, docType) {
        const modal = document.getElementById('documentViewerModal');
        const title = document.getElementById('documentViewerTitle');
        const subtitle = document.getElementById('documentViewerSubtitle');
        const content = document.getElementById('documentViewerContent');
        
        title.textContent = docName;
        subtitle.textContent = docType;
        
        // Use the path directly - it already contains the full path from database
        const docUrl = `/storage/${docPath}`;
        
            
            // Check file extension to determine how to display
            const fileExtension = docPath.split('.').pop().toLowerCase();
            
            if (['pdf'].includes(fileExtension)) {
                content.innerHTML = `
                    <div class="w-full">
                        <iframe src="${docUrl}" class="w-full h-96 rounded-lg border border-gray-300" frameborder="0"></iframe>
                        <div class="mt-4 flex justify-center">
                            <a href="${docUrl}" download="${docName}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                                <i class="fas fa-download"></i>
                                <span>Download Document</span>
                            </a>
                        </div>
                    </div>
                `;
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                content.innerHTML = `
                    <div class="w-full">
                        <img src="${docUrl}" alt="${docName}" class="max-w-full max-h-96 rounded-lg border border-gray-300">
                        <div class="mt-4 flex justify-center">
                            <a href="${docUrl}" download="${docName}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                                <i class="fas fa-download"></i>
                                <span>Download Image</span>
                            </a>
                        </div>
                    </div>
                `;
            } else {
                content.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-file text-6xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-4">This document type cannot be previewed.</p>
                        <a href="${docUrl}" download="${docName}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                            <i class="fas fa-download"></i>
                            <span>Download Document</span>
                        </a>
                    </div>
                `;
            }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Add user form submission
    document.getElementById('addUserForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        try {
            const response = await fetch('/admin/users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            const result = await response.json();
            
            if (result.success) {
                alert(result.message);
                closeAddUserModal();
                loadUsers(); // Refresh the table
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error creating user:', error);
            alert('Error creating user');
        }
    });

    // User action functions
    function editUser(userId) {
        alert('Edit user: ' + userId);
        // Implement edit functionality
    }
    function handleStatusUpdate(userId, currentStatus) {
        // Toggle between active and banned
        const newStatus = currentStatus === 'active' ? 'banned' : 'active';
        updateUserStatus(userId, newStatus);
    }

    // Update the button title to be more accurate
    function updateStatusButtonTitle(currentStatus) {
        if (currentStatus === 'active') {
            return 'Deactivate';
        } else if (currentStatus === 'inactive') {
            return 'Activate';
        } else if (currentStatus === 'banned') {
            return 'Unban';
        }
        return 'Toggle Status';
    }

    async function updateUserStatus(userId, status) {
        if (confirm(`Are you sure you want to ${status === 'active' ? 'activate' : 'banned'} this user?`)) {
            try {
                const response = await fetch(`/admin/users/${userId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status })
                });

                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                    loadUsers();
                }
            } catch (error) {
                console.error('Error updating user status:', error);
            }
        }
    }

    async function archiveUser(userId) {
        if (confirm('Are you sure you want to archive this user? This will set their status to inactive.')) {
            try {
                const response = await fetch(`/admin/users/${userId}/archive`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });

                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                    loadUsers();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error archiving user:', error);
                alert('Error archiving user');
            }
        }
    }

    async function approveKyc(kycId) {
        if (confirm('Are you sure you want to approve this KYC document?')) {
            try {
                const response = await fetch(`/admin/kyc/${kycId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                    loadKycDocuments();
                    loadUsers();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error approving KYC:', error);
                alert('Error approving KYC document. Please try again.');
            }
        }
    }

    async function rejectKyc(kycId) {
        if (confirm('Are you sure you want to reject this KYC document?')) {
            try {
                const response = await fetch(`/admin/kyc/${kycId}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                // Check if response is OK before parsing JSON
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                
                if (result.success) {
                    alert(result.message);
                    loadKycDocuments();
                    loadUsers();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error rejecting KYC:', error);
                alert('Error rejecting KYC document. Please try again.');
            }
        }
    }
</script>
@endpush