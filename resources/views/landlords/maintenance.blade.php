@extends('layouts.landlord')

@section('title', 'Maintenance_landlord - SmartRent')
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
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
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
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
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
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['in_progress'] }}</p>
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
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['high_priority'] }}</p>
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
                    <option>In Progress</option>
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
        @foreach($maintenanceRequests as $request)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($request->priority == 'HIGH') bg-red-100 text-red-800
                        @elseif($request->priority == 'MEDIUM') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ $request->priority }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($request->status == 'PENDING') bg-orange-100 text-orange-800
                        @elseif($request->status == 'IN_PROGRESS') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ str_replace('_', ' ', $request->status) }}
                    </span>
                </div>
                <button class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $request->title }}</h3>
            <p class="text-gray-600 text-sm mb-4">{{ $request->description }}</p>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-home w-4 mr-3"></i>
                    <span>
                        @if($request->unit && $request->unit->property)
                            {{ $request->unit->property->name }} - {{ $request->unit->unit_number }}
                        @else
                            Property not found
                        @endif
                    </span>
                    <span class="ml-auto font-medium">Maintenance</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-user w-4 mr-3"></i>
                    <span>{{ $request->tenant->name ?? 'Unknown Tenant' }}</span>
                    <span class="ml-auto font-medium">-</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-calendar w-4 mr-3"></i>
                    <span>{{ $request->requested_at ? $request->requested_at->format('n/j/Y') : 'No date' }}</span>
                    <span class="ml-auto">
                        @if($request->assignedTechnician)
                            Assigned to {{ $request->assignedTechnician->name }}
                        @else
                            Not assigned
                        @endif
                    </span>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2 update-status-btn" data-request-id="{{ $request->request_id }}">
                    <i class="fas fa-sync-alt text-sm"></i>
                    <span>Update Status</span>
                </button>
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors view-details-btn" data-request-id="{{ $request->request_id }}">
                    View Details
                </button>
            </div>
        </div>
        @endforeach
        
        @if($maintenanceRequests->isEmpty())
        <div class="col-span-2 text-center py-12">
            <i class="fas fa-wrench text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No maintenance requests</h3>
            <p class="text-gray-500">Maintenance requests from your tenants will appear here.</p>
        </div>
        @endif
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
        <form id="maintenanceRequestForm" class="p-6" action="{{ route('landlord.maintenance.store') }}" method="POST">
            @csrf
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
                        <input type="text" name="title" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., AC Unit Not Working">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Priority Level <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Priority</option>
                            <option value="LOW">Low</option>
                            <option value="MEDIUM">Medium</option>
                            <option value="HIGH">High</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" required rows="3"
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
                        <select name="property_id" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Property</option>
                            @foreach($properties as $property)
                            <option value="{{ $property->property_id }}">{{ $property->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Unit <span class="text-red-500">*</span>
                        </label>
                        <select name="unit_id" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Unit</option>
                            <!-- Units will be loaded via JavaScript based on selected property -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Assign to Technician
                        </label>
                        <select name="assigned_staff_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Technician</option>
                            @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                            @endforeach
                        </select>
                    </div>
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
    const propertySelect = document.querySelector('select[name="property_id"]');
    const unitSelect = document.querySelector('select[name="unit_id"]');

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
        unitSelect.innerHTML = '<option value="">Select Unit</option>';
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

    // Load units when property is selected
    propertySelect.addEventListener('change', function() {
        const propertyId = this.value;
        unitSelect.innerHTML = '<option value="">Loading units...</option>';
        
        if (propertyId) {
            fetch(`/landlord/properties/${propertyId}/units`)
                .then(response => response.json())
                .then(units => {
                    unitSelect.innerHTML = '<option value="">Select Unit</option>';
                    units.forEach(unit => {
                        unitSelect.innerHTML += `<option value="${unit.unit_id}">${unit.unit_number}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error loading units:', error);
                    unitSelect.innerHTML = '<option value="">Error loading units</option>';
                });
        } else {
            unitSelect.innerHTML = '<option value="">Select Unit</option>';
        }
    });
</script>
@endpush