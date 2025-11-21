@extends('layouts.admin')

@section('title', 'Maintenance - SmartRent')
@section('page-title', 'Maintenance')
@section('page-description', 'Track and manage maintenance requests across all properties.')

@section('header-actions')
    <button id="newRequestBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center space-x-2">
        <i class="fas fa-plus text-sm"></i>
        <span>New Request</span>
    </button>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Requests -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wrench text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Requests</p>
                    <p class="text-3xl font-bold text-gray-900">6</p>
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
                    <p class="text-3xl font-bold text-gray-900">3</p>
                </div>                            
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">In Progress</p>
                    <p class="text-3xl font-bold text-gray-900">1</p>
                </div>                           
            </div>
        </div>

        <!-- High Priority -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">High Priority</p>
                    <p class="text-3xl font-bold text-gray-900">2</p>
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
                    <input type="text" placeholder="Search requests..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <div class="ml-4 flex space-x-3">
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option>All Status</option>
                    <option>Pending</option>
                    <option>On Progress</option>
                    <option>Completed</option>
                </select>
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option>All Priority</option>
                    <option>Low</option>
                    <option>Medium</option>
                    <option>High</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Maintenance Requests Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- AC Unit Not Cooling -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        HIGH
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        PENDING
                    </span>
                </div>
                <button class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-2">AC Unit Not Cooling</h3>
            <p class="text-gray-600 text-sm mb-4">Air conditioning in master bedroom is running but not cooling effectively.</p>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-home w-4 mr-3"></i>
                    <span>Sunset Villa #12</span>
                    <span class="ml-auto font-medium">HVAC</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-user w-4 mr-3"></i>
                    <span>Sarah Johnson</span>
                    <span class="ml-auto font-medium">$250</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-calendar w-4 mr-3"></i>
                    <span>1/8/2024</span>
                    <span class="ml-auto">Assigned to Mike Rodriguez</span>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
                    <i class="fas fa-sync-alt text-sm"></i>
                    <span>Update Status</span>
                </button>
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    View Details
                </button>
            </div>
        </div>

        <!-- Kitchen Sink Leak -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        MEDIUM
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        IN PROGRESS
                    </span>
                </div>
                <button class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Kitchen Sink Leak</h3>
            <p class="text-gray-600 text-sm mb-4">Water leak under kitchen sink, causing damage to cabinet floor.</p>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-home w-4 mr-3"></i>
                    <span>Downtown Loft #3</span>
                    <span class="ml-auto font-medium">Plumbing</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-user w-4 mr-3"></i>
                    <span>Michael Chen</span>
                    <span class="ml-auto font-medium">$180</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-calendar w-4 mr-3"></i>
                    <span>1/7/2024</span>
                    <span class="ml-auto">Assigned to Tom Wilson</span>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
                    <i class="fas fa-clock text-sm"></i>
                    <span>Update Status</span>
                </button>
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    View Details
                </button>
            </div>
        </div>

        <!-- Front Door Lock Malfunction -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        HIGH
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        PENDING
                    </span>
                </div>
                <button class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Front Door Lock Malfunction</h3>
            <p class="text-gray-600 text-sm mb-4">Smart lock not responding to key fob or mobile app.</p>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-home w-4 mr-3"></i>
                    <span>Garden Court #15</span>
                    <span class="ml-auto font-medium">Security</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-user w-4 mr-3"></i>
                    <span>Emily Rodriguez</span>
                    <span class="ml-auto font-medium">$120</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-calendar w-4 mr-3"></i>
                    <span>1/6/2024</span>
                    <span class="ml-auto">Assigned to Lisa Chen</span>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
                    <i class="fas fa-sync-alt text-sm"></i>
                    <span>Update Status</span>
                </button>
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    View Details
                </button>
            </div>
        </div>

        <!-- Light Fixture Replacement -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        LOW
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        COMPLETED
                    </span>
                </div>
                <button class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Light Fixture Replacement</h3>
            <p class="text-gray-600 text-sm mb-4">Bathroom light fixture flickering and needs replacement.</p>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-home w-4 mr-3"></i>
                    <span>Tech Hub #22</span>
                    <span class="ml-auto font-medium">Electrical</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-user w-4 mr-3"></i>
                    <span>David Thompson</span>
                    <span class="ml-auto font-medium">$95</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-calendar w-4 mr-3"></i>
                    <span>1/5/2024</span>
                    <span class="ml-auto">Assigned to Lisa Chen</span>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
                    <i class="fas fa-sync-alt text-sm"></i>
                    <span>Update Status</span>
                </button>
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    View Details
                </button>
            </div>
        </div>
    </div>
@endsection

@push('modals')
<!-- New Request Modal -->
<div id="newRequestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wrench text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">New Maintenance Request</h2>
                    <p class="text-sm text-gray-500">Create a new maintenance request for your property</p>
                </div>
            </div>
            <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="maintenanceRequestForm" class="p-6">
            <!-- Request Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Request Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Request Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="requestTitle" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., AC Unit Not Working">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="category" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Category</option>
                            <option value="HVAC">HVAC</option>
                            <option value="Plumbing">Plumbing</option>
                            <option value="Electrical">Electrical</option>
                            <option value="Security">Security</option>
                            <option value="Appliances">Appliances</option>
                            <option value="General">General Maintenance</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Priority Level <span class="text-red-500">*</span>
                        </label>
                        <select id="priority" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Priority</option>
                            <option value="LOW">Low</option>
                            <option value="MEDIUM">Medium</option>
                            <option value="HIGH">High</option>
                            <option value="EMERGENCY">Emergency</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Estimated Cost
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" id="estimatedCost" min="0" step="0.01"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" required rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Provide detailed description of the issue..."></textarea>
                </div>
            </div>

            <!-- Property Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-building text-blue-600 mr-2"></i>
                    Property Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Property <span class="text-red-500">*</span>
                        </label>
                        <select id="property" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Property</option>
                            <option value="Sunset Villa">Sunset Villa</option>
                            <option value="Downtown Loft">Downtown Loft</option>
                            <option value="Garden Court">Garden Court</option>
                            <option value="Tech Hub">Tech Hub</option>
                            <option value="Riverside Apartments">Riverside Apartments</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Unit Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="unitNumber" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., #12, A-101">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tenant Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="tenantName" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Enter tenant name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tenant Phone
                        </label>
                        <input type="tel" id="tenantPhone" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="(555) 123-4567">
                    </div>
                </div>
            </div>

            <!-- Assignment Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user-cog text-blue-600 mr-2"></i>
                    Assignment Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Assign to Technician
                        </label>
                        <select id="assignedTo" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Technician</option>
                            <option value="Mike Rodriguez">Mike Rodriguez</option>
                            <option value="Tom Wilson">Tom Wilson</option>
                            <option value="Lisa Chen">Lisa Chen</option>
                            <option value="David Martinez">David Martinez</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Preferred Date
                        </label>
                        <input type="date" id="preferredDate" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Special Instructions
                    </label>
                    <textarea id="specialInstructions" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Any special instructions for the technician..."></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <button type="button" id="cancelBtn" 
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-plus text-sm"></i>
                    <span>Create Request</span>
                </button>
            </div>
        </form>
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
    const tenantPhoneInput = document.getElementById('tenantPhone');

    // Open modal
    newRequestBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    // Close modal functions
    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        form.reset();
    }

    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Phone number formatting
    tenantPhoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 6) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
        } else if (value.length >= 3) {
            value = value.replace(/(\d{3})(\d{0,3})/, '($1) $2');
        }
        e.target.value = value;
    });

    // Form submission
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Collect form data
        const formData = {
            title: document.getElementById('requestTitle').value,
            category: document.getElementById('category').value,
            priority: document.getElementById('priority').value,
            estimatedCost: document.getElementById('estimatedCost').value,
            description: document.getElementById('description').value,
            property: document.getElementById('property').value,
            unitNumber: document.getElementById('unitNumber').value,
            tenantName: document.getElementById('tenantName').value,
            tenantPhone: document.getElementById('tenantPhone').value,
            assignedTo: document.getElementById('assignedTo').value,
            preferredDate: document.getElementById('preferredDate').value,
            specialInstructions: document.getElementById('specialInstructions').value,
            dateCreated: new Date().toISOString().split('T')[0],
            status: 'PENDING'
        };

        console.log('New Maintenance Request:', formData);
        
        // Show success message
        alert('Maintenance request created successfully!');
        
        // Close modal and reset form
        closeModal();
    });
</script>
@endpush