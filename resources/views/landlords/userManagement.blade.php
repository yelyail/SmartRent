@extends('layouts.landlord')

@section('title', 'User Management - SmartRent')
@section('page-title', 'Tenants')
@section('page-description', 'Manage tenant information, leases, and communications.')

@section('header-actions')
    <button id="addTenantBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center space-x-2">
        <i class="fas fa-plus text-sm"></i>
        <span>Add Tenant</span>
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
                    <p class="text-3xl font-bold text-gray-900">6</p>
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
                    <p class="text-3xl font-bold text-gray-900">4</p>
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
                    <p class="text-3xl font-bold text-gray-900">1</p>
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
                    <p class="text-3xl font-bold text-gray-900">2</p>
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
                    <option value="Active">Active</option>
                    <option value="Notice">Notice</option>
                    <option value="Expired">Expired</option>
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
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Lease</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Rent</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Status</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Payment</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="tenantsTableBody" class="divide-y divide-gray-200">
                    <!-- Sarah Johnson -->
                    <tr class="hover:bg-gray-50 transition-colors" data-status="Active" data-name="Sarah Johnson" data-property="Sunset Villa #12">
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-3">
                                <img src="https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=100&h=100&fit=crop" alt="Sarah Johnson" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <p class="font-medium text-gray-900">Sarah Johnson</p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span class="flex items-center space-x-1">
                                            <i class="fas fa-envelope text-xs"></i>
                                            <span>sarah.johnson@email.com</span>
                                        </span>
                                        <span class="flex items-center space-x-1">
                                            <i class="fas fa-phone text-xs"></i>
                                            <span>(555) 123-4567</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-home text-gray-400"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Sunset Villa #12</p>
                                    <p class="text-sm text-gray-500">Unit 12A</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar text-gray-400"></i>
                                <div>
                                    <p class="font-medium text-gray-900">1/15/2024</p>
                                    <p class="text-sm text-gray-500">to 1/14/2025</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-medium text-gray-900">$2,800</p>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-sm text-green-600 font-medium">Current</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 relative">
                            <button class="text-gray-400 hover:text-gray-600 transition-colors action-menu-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="action-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Details</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Tenant</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Send Message</a>
                                <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Remove Tenant</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Add other tenant rows here (Michael Chen, Emily Rodriguez, etc.) -->
                    <!-- ... -->
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Tenant Modal -->
<div id="addTenantModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-plus text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Add New Tenant</h2>
                    <p class="text-sm text-gray-500">Fill in the tenant information below</p>
                </div>
            </div>
            <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="addTenantForm" class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                        <i class="fas fa-user text-blue-600"></i>
                        <span>Personal Information</span>
                    </h3>
                </div>

                <!-- First Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="firstName" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" placeholder="Enter first name">
                </div>

                <!-- Last Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="lastName" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" placeholder="Enter last name">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" placeholder="Enter email address">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="phone" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" placeholder="(555) 123-4567">
                </div>

                <!-- Property Information -->
                <div class="lg:col-span-2 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                        <i class="fas fa-home text-blue-600"></i>
                        <span>Property Information</span>
                    </h3>
                </div>

                <!-- Property -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Property <span class="text-red-500">*</span>
                    </label>
                    <select id="property" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        <option value="">Select a property</option>
                        <option value="sunset-villa">Sunset Villa</option>
                        <option value="downtown-loft">Downtown Loft</option>
                        <option value="garden-court">Garden Court</option>
                        <option value="tech-hub">Tech Hub</option>
                        <option value="historic-heights">Historic Heights</option>
                        <option value="riverside-manor">Riverside Manor</option>
                    </select>
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Unit Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="unit" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" placeholder="e.g., 12A">
                </div>

                <!-- Monthly Rent -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Monthly Rent <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                        <input type="number" id="rent" required class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" placeholder="2800">
                    </div>
                </div>

                <!-- Lease Information -->
                <div class="lg:col-span-2 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                        <span>Lease Information</span>
                    </h3>
                </div>

                <!-- Lease Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lease Start Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="leaseStart" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                </div>

                <!-- Lease End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lease End Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="leaseEnd" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                </div>

                <!-- Additional Notes -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Additional Notes
                    </label>
                    <textarea id="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-none" placeholder="Any additional information about the tenant..."></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <button type="button" id="cancelBtn" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center space-x-2">
                    <i class="fas fa-save"></i>
                    <span>Add Tenant</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Modal functionality
    const addTenantBtn = document.getElementById('addTenantBtn');
    const addTenantModal = document.getElementById('addTenantModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const addTenantForm = document.getElementById('addTenantForm');

    // Open modal
    addTenantBtn.addEventListener('click', () => {
        addTenantModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    // Close modal function
    function closeModal() {
        addTenantModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        addTenantForm.reset();
    }

    // Close modal events
    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside
    addTenantModal.addEventListener('click', (e) => {
        if (e.target === addTenantModal) {
            closeModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !addTenantModal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Phone number formatting
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 6) {
            value = `(${value.slice(0,3)}) ${value.slice(3,6)}-${value.slice(6,10)}`;
        } else if (value.length >= 3) {
            value = `(${value.slice(0,3)}) ${value.slice(3)}`;
        }
        e.target.value = value;
    });

    // Form validation
    function validateForm() {
        const firstName = document.getElementById('firstName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const property = document.getElementById('property').value;
        const unit = document.getElementById('unit').value.trim();
        const rent = document.getElementById('rent').value;
        const leaseStart = document.getElementById('leaseStart').value;
        const leaseEnd = document.getElementById('leaseEnd').value;
        
        if (!firstName || !lastName || !email || !phone || !property || !unit || !rent || !leaseStart || !leaseEnd) {
            alert('Please fill in all required fields.');
            return false;
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address.');
            return false;
        }
        
        // Phone validation (basic)
        const phoneRegex = /^\(\d{3}\) \d{3}-\d{4}$/;
        if (!phoneRegex.test(phone)) {
            alert('Please enter a valid phone number in the format (555) 123-4567.');
            return false;
        }
        
        // Date validation
        if (new Date(leaseStart) >= new Date(leaseEnd)) {
            alert('Lease end date must be after lease start date.');
            return false;
        }
        
        return true;
    }

    // Form submission
    addTenantForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }
        
        // Get form data
        const tenantData = {
            firstName: document.getElementById('firstName').value,
            lastName: document.getElementById('lastName').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            property: document.getElementById('property').value,
            unit: document.getElementById('unit').value,
            rent: document.getElementById('rent').value,
            leaseStart: document.getElementById('leaseStart').value,
            leaseEnd: document.getElementById('leaseEnd').value,
            notes: document.getElementById('notes').value
        };

        // Here you would typically send the data to your backend
        console.log('New tenant data:', tenantData);
        
        // Show success message (you can replace this with actual backend integration)
        alert('Tenant added successfully!');
        
        // Close modal and reset form
        closeModal();
    });

    // Search and filter functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const clearFilters = document.getElementById('clearFilters');
    const tenantsTableBody = document.getElementById('tenantsTableBody');
    const tenantRows = tenantsTableBody.querySelectorAll('tr');

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

    // Action menu functionality
    const actionMenuButtons = document.querySelectorAll('.action-menu-btn');
    
    actionMenuButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            
            // Close all other open menus
            document.querySelectorAll('.action-menu').forEach(menu => {
                if (menu !== button.nextElementSibling) {
                    menu.classList.add('hidden');
                }
            });
            
            // Toggle current menu
            const menu = button.nextElementSibling;
            menu.classList.toggle('hidden');
        });
    });

    // Close menus when clicking elsewhere
    document.addEventListener('click', () => {
        document.querySelectorAll('.action-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    });
</script>
@endpush