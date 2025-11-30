@extends('layouts.landlord')

@section('title', 'Maintenance_landlord - SmartRent')
@section('page-description', 'Track and manage maintenance requests across all properties.')

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
                    <i class="fas fa-tools text-blue-600 text-lg"></i>
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
                    <input type="text" id="searchInput" placeholder="Search requests..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <div class="ml-4 flex space-x-3">
                <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
                <select id="priorityFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Maintenance Requests Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="requestsContainer">
        @foreach($maintenanceRequests as $request)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 maintenance-request-card" 
            data-status="{{ strtolower($request->status) }}" 
            data-priority="{{ strtolower($request->priority) }}"
            data-search="{{ strtolower($request->title . ' ' . $request->description) }}"
            data-tenant-phone="{{ $request->user->phone_num ?? '' }}"
            data-requested-at="{{ $request->requested_at ? $request->requested_at->toISOString() : $request->created_at->toISOString() }}"
            data-updated-at="{{ $request->updated_at->toISOString() }}">
            <div class="flex items-start justify-between mb-4">
                <div class="flex space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($request->priority == 'high') bg-red-100 text-red-800
                        @elseif($request->priority == 'medium') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ $request->priority }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($request->status == 'pending') bg-orange-100 text-orange-800
                        @elseif($request->status == 'in_progress') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ str_replace('_', ' ', $request->status) }}
                    </span>
                </div>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $request->title }}</h3>
            <p class="text-gray-600 text-sm mb-4">{{ $request->description }}</p>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-home w-4 mr-3"></i>
                    <span>
                        @if($request->unit && $request->unit->property)
                            {{ $request->unit->property->property_name }} - {{ $request->unit->unit_name }} #{{ $request->unit->unit_num }}
                        @else
                            Property not found
                        @endif
                    </span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-user w-4 mr-3"></i>
                    <span>{{ $request->user->first_name ?? 'Unknown' }} {{ $request->user->last_name ?? 'Tenant' }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-phone w-4 mr-3"></i>
                    <span>{{ $request->user->phone_num ?? 'No contact number' }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-calendar w-4 mr-3"></i>
                    <span>
                        @if($request->requested_at)
                            {{ \Carbon\Carbon::parse($request->requested_at)->format('M j, Y') }}
                        @else
                            {{ $request->created_at->format('M j, Y') }}
                        @endif
                    </span>
                    @if($request->assignedStaff)
                        <span class="ml-auto">Assigned to {{ $request->assignedStaff->first_name }} {{ $request->assignedStaff->last_name }}</span>
                    @else
                        <span class="ml-auto text-gray-400">Not assigned</span>
                    @endif
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2 view-details-btn" data-request-id="{{ $request->request_id }}">
                    <i class="fas fa-eye text-sm"></i>
                    <span>View Details</span>
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
<!-- View Details Modal -->
<div id="viewDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wrench text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900" id="modalTitle">Maintenance Request Details</h2>
                    <p class="text-sm text-gray-500">Complete information about this maintenance request</p>
                </div>
            </div>
            <button id="closeDetailsModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <!-- Request Status & Priority -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex space-x-2" id="statusBadges">
                    <!-- Status and priority badges will be inserted here -->
                </div>
                <div class="text-sm text-gray-500" id="requestId">
                    <!-- Request ID will be inserted here -->
                </div>
            </div>

            <!-- Request Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Request Information
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <p class="text-gray-900 font-medium" id="detailTitle"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <p class="text-gray-600 text-sm bg-gray-50 p-3 rounded-lg" id="detailDescription"></p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date Requested</label>
                            <p class="text-gray-600" id="detailRequestedAt"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-600" id="detailUpdatedAt"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property & Tenant Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-building text-blue-600 mr-2"></i>
                    Property & Tenant Information
                </h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Property</label>
                            <p class="text-gray-600" id="detailProperty"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <p class="text-gray-600" id="detailUnit"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tenant Name</label>
                            <p class="text-gray-600" id="detailTenant"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tenant Contact</label>
                            <p class="text-gray-600" id="detailTenantContact"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user-cog text-blue-600 mr-2"></i>
                    Assignment Information
                </h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Staff</label>
                            <p class="text-gray-600" id="detailAssignedStaff">
                                <span class="text-gray-400">Not assigned</span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Staff Position</label>
                            <p class="text-gray-600" id="detailStaffPosition">
                                <span class="text-gray-400">-</span>
                            </p>
                        </div>
                    </div>
                    <div id="assignmentDatesSection" class="hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Date</label>
                                <p class="text-gray-600" id="detailAssignedDate"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Completed Date</label>
                                <p class="text-gray-600" id="detailCompletedDate">
                                    <span class="text-gray-400">Not completed</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-history text-blue-600 mr-2"></i>
                    Request Timeline
                </h3>
                <div class="space-y-3" id="timeline">
                    <!-- Timeline items will be inserted here -->
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200">
            <button type="button" id="closeDetailsBtn" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // View Details functionality
    document.addEventListener('DOMContentLoaded', function() {
        const viewDetailsModal = document.getElementById('viewDetailsModal');
        const closeDetailsModalBtn = document.getElementById('closeDetailsModalBtn');
        const closeDetailsBtn = document.getElementById('closeDetailsBtn');

        // Close modal functions
        function closeDetailsModal() {
            viewDetailsModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        closeDetailsModalBtn.addEventListener('click', closeDetailsModal);
        closeDetailsBtn.addEventListener('click', closeDetailsModal);

        // Close modal when clicking outside
        viewDetailsModal.addEventListener('click', (e) => {
            if (e.target === viewDetailsModal) {
                closeDetailsModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !viewDetailsModal.classList.contains('hidden')) {
                closeDetailsModal();
            }
        });

        // View details button functionality
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const requestId = this.getAttribute('data-request-id');
                await loadRequestDetails(requestId);
                viewDetailsModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        // Load request details
        async function loadRequestDetails(requestId) {
            try {
                // Show loading state
                showLoadingState();

                console.log('Loading details for request ID:', requestId);

                // Try to fetch from API first
                try {
                    const response = await fetch(`/landlord/maintenance-requests/${requestId}/details`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const requestData = await response.json();
                        console.log('API response data:', requestData);
                        populateModalDetails(requestData);
                        return;
                    } else {
                        console.warn('API request failed with status:', response.status);
                    }
                } catch (apiError) {
                    console.warn('API call failed:', apiError);
                }

                // Fallback to card data
                console.log('Falling back to card data for request ID:', requestId);
                await loadFromCardData(requestId);

            } catch (error) {
                console.error('Error loading request details:', error);
                alert('Error loading request details. Please try again.');
            }
        }

        // Fallback function to load data from card
        async function loadFromCardData(requestId) {
            try {
                const card = document.querySelector(`.view-details-btn[data-request-id="${requestId}"]`).closest('.maintenance-request-card');
                if (!card) {
                    throw new Error('Card not found for request ID: ' + requestId);
                }

                console.log('Found card for request:', card);

                // Extract data from the card using data attributes
                const title = card.querySelector('h3.text-lg')?.textContent || 'No title';
                const description = card.querySelector('.text-gray-600.text-sm')?.textContent || 'No description';
                
                // Get status and priority from badges
                const priorityBadge = card.querySelector('.inline-flex.items-center:first-child');
                const statusBadge = card.querySelector('.inline-flex.items-center:last-child');
                
                const priority = priorityBadge ? priorityBadge.textContent.trim().toLowerCase() : 'unknown';
                const status = statusBadge ? statusBadge.textContent.trim().toLowerCase().replace(' ', '_') : 'unknown';

                // Extract property and unit information
                const propertyUnitElement = card.querySelector('.flex.items-center.text-sm.text-gray-600:first-child span');
                const propertyUnit = propertyUnitElement ? propertyUnitElement.textContent.trim() : 'Property not found';

                // Extract tenant information
                const tenantElement = card.querySelector('.flex.items-center.text-sm.text-gray-600:nth-child(2) span');
                const tenantName = tenantElement ? tenantElement.textContent.trim() : 'Unknown Tenant';

                // Extract tenant phone number
                const tenantPhoneElement = card.querySelector('.flex.items-center.text-sm.text-gray-600:nth-child(3) span');
                const tenantPhone = tenantPhoneElement ? tenantPhoneElement.textContent.trim() : 'No contact number';

                // Extract dates from data attributes (this is the key fix!)
                const requestedAt = card.getAttribute('data-requested-at');
                const updatedAt = card.getAttribute('data-updated-at');

                // Extract assigned staff information
                const assignedElement = card.querySelector('.flex.items-center.text-sm.text-gray-600:nth-child(4) .ml-auto');
                const assignedInfo = assignedElement ? assignedElement.textContent.trim() : 'Not assigned';

                // Create fallback data object
                const fallbackData = {
                    request_id: requestId,
                    title,
                    description,
                    status,
                    priority,
                    property_name: propertyUnit.split(' - ')[0] || 'N/A',
                    unit_name: propertyUnit.split(' - ')[1] || 'N/A',
                    tenant_name: tenantName,
                    tenant_phone: tenantPhone,
                    requested_at: requestedAt, // Use the ISO string from data attribute
                    updated_at: updatedAt, // Use the ISO string from data attribute
                    assigned_staff: assignedInfo !== 'Not assigned' ? assignedInfo.replace('Assigned to ', '') : null,
                    staff_position: assignedInfo !== 'Not assigned' ? 'Maintenance Staff' : null
                };

                console.log('Fallback data:', fallbackData);
                populateModalDetails(fallbackData);

            } catch (error) {
                console.error('Error loading from card data:', error);
                // Show error state
                showErrorState();
            }
        }

        function showLoadingState() {
            document.getElementById('modalTitle').textContent = 'Loading...';
            document.getElementById('detailTitle').textContent = 'Loading...';
            document.getElementById('detailDescription').textContent = 'Loading...';
            document.getElementById('detailRequestedAt').textContent = 'Loading...';
            document.getElementById('detailUpdatedAt').textContent = 'Loading...';
            document.getElementById('detailProperty').textContent = 'Loading...';
            document.getElementById('detailUnit').textContent = 'Loading...';
            document.getElementById('detailTenant').textContent = 'Loading...';
            document.getElementById('detailTenantContact').textContent = 'Loading...';
        }

        function showErrorState() {
            document.getElementById('modalTitle').textContent = 'Error Loading Details';
            document.getElementById('detailTitle').textContent = 'Error';
            document.getElementById('detailDescription').textContent = 'Unable to load request details. Please try again.';
            document.getElementById('detailRequestedAt').textContent = 'N/A';
            document.getElementById('detailUpdatedAt').textContent = 'N/A';
            document.getElementById('detailProperty').textContent = 'N/A';
            document.getElementById('detailUnit').textContent = 'N/A';
            document.getElementById('detailTenant').textContent = 'N/A';
            document.getElementById('detailTenantContact').textContent = 'N/A';
        }

        function populateModalDetails(data) {
            console.log('Populating modal with data:', data);
            
            // Reset modal title
            document.getElementById('modalTitle').textContent = 'Maintenance Request Details';
            
            // Set request ID
            document.getElementById('requestId').textContent = `Request #${data.request_id}`;

            // Set status badges
            const statusBadges = document.getElementById('statusBadges');
            statusBadges.innerHTML = `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    ${getPriorityClass(data.priority)}">
                    ${data.priority.toUpperCase()}
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    ${getStatusClass(data.status)}">
                    ${data.status.replace('_', ' ').toUpperCase()}
                </span>
            `;

            // Set request information
            document.getElementById('detailTitle').textContent = data.title;
            document.getElementById('detailDescription').textContent = data.description;
            
            // Format and set dates - USE THE ISO STRINGS FROM DATA ATTRIBUTES
            const requestedAtFormatted = formatDate(data.requested_at);
            const updatedAtFormatted = formatDate(data.updated_at);
            
            console.log('Requested At:', data.requested_at, '->', requestedAtFormatted);
            console.log('Updated At:', data.updated_at, '->', updatedAtFormatted);
            
            document.getElementById('detailRequestedAt').textContent = requestedAtFormatted;
            document.getElementById('detailUpdatedAt').textContent = updatedAtFormatted;

            // Set property and tenant information
            document.getElementById('detailProperty').textContent = data.property_name || 'N/A';
            document.getElementById('detailUnit').textContent = data.unit_name || 'N/A';
            document.getElementById('detailTenant').textContent = data.tenant_name || 'Unknown Tenant';
            document.getElementById('detailTenantContact').textContent = data.tenant_phone || 'No contact number';

            // Set assignment information
            if (data.assigned_staff) {
                document.getElementById('detailAssignedStaff').textContent = data.assigned_staff;
                document.getElementById('detailStaffPosition').textContent = data.staff_position || 'Maintenance Staff';
                document.getElementById('assignmentDatesSection').classList.remove('hidden');
                document.getElementById('detailAssignedDate').textContent = formatDate(data.assigned_date) || 'Not set';
                if (data.status === 'completed' && data.completed_date) {
                    document.getElementById('detailCompletedDate').textContent = formatDate(data.completed_date);
                } else {
                    document.getElementById('detailCompletedDate').innerHTML = '<span class="text-gray-400">Not completed</span>';
                }
            } else {
                document.getElementById('detailAssignedStaff').innerHTML = '<span class="text-gray-400">Not assigned</span>';
                document.getElementById('detailStaffPosition').innerHTML = '<span class="text-gray-400">-</span>';
                document.getElementById('assignmentDatesSection').classList.add('hidden');
            }

            // Set timeline
            populateTimeline(data);
        }

        function formatDate(dateString) {
            console.log('🔧 Formatting date:', dateString, 'Type:', typeof dateString);
            
            if (!dateString || dateString === 'null' || dateString === 'undefined') {
                console.log('❌ No valid date string provided');
                return 'Date not available';
            }
            
            try {
                // Parse the date from ISO string
                const date = new Date(dateString);
                
                console.log('📅 Parsed date object:', date);
                
                // Check if the date is valid
                if (isNaN(date.getTime())) {
                    console.log('❌ Invalid date after parsing');
                    return 'Date not available';
                }
                
                // Format the date nicely
                const options = { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                const formatted = date.toLocaleDateString('en-US', options);
                console.log('✅ Successfully formatted date:', formatted);
                return formatted;
                
            } catch (error) {
                console.error('❌ Error formatting date:', error);
                return 'Date not available';
            }
        }

        function getPriorityClass(priority) {
            switch(priority) {
                case 'high': return 'bg-red-100 text-red-800';
                case 'medium': return 'bg-yellow-100 text-yellow-800';
                case 'low': return 'bg-green-100 text-green-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        function getStatusClass(status) {
            switch(status) {
                case 'pending': return 'bg-orange-100 text-orange-800';
                case 'in_progress': return 'bg-blue-100 text-blue-800';
                case 'completed': return 'bg-green-100 text-green-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        function populateTimeline(data) {
            const timeline = document.getElementById('timeline');
            
            const requestedAtFormatted = formatDate(data.requested_at);
            const updatedAtFormatted = formatDate(data.updated_at);
            
            timeline.innerHTML = `
                <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Request Submitted</p>
                        <p class="text-sm text-gray-500">by ${data.tenant_name || 'Tenant'}</p>
                        <p class="text-xs text-gray-400">${requestedAtFormatted}</p>
                    </div>
                </div>
                ${data.assigned_staff ? `
                <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Assigned to Staff</p>
                        <p class="text-sm text-gray-500">${data.assigned_staff}</p>
                        <p class="text-xs text-gray-400">${formatDate(data.assigned_date) || 'Date not available'}</p>
                    </div>
                </div>
                ` : `
                <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 bg-gray-300 rounded-full mt-2 flex-shrink-0"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Awaiting Assignment</p>
                        <p class="text-sm text-gray-500">No staff assigned yet</p>
                        <p class="text-xs text-gray-400">Pending assignment</p>
                    </div>
                </div>
                `}
                <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 
                        ${data.status === 'completed' ? 'bg-green-500' : 
                          data.status === 'in_progress' ? 'bg-blue-500' : 
                          data.status === 'pending' ? 'bg-orange-500' : 'bg-gray-500'} 
                        rounded-full mt-2 flex-shrink-0"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Current Status</p>
                        <p class="text-sm text-gray-500">${data.status.replace('_', ' ').toUpperCase()}</p>
                        <p class="text-xs text-gray-400">Last updated: ${updatedAtFormatted}</p>
                    </div>
                </div>
            `;
        }

        // Search and Filter functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const priorityFilter = document.getElementById('priorityFilter');
        const requestCards = document.querySelectorAll('.maintenance-request-card');

        function filterRequests() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            const priorityValue = priorityFilter.value;

            let visibleCount = 0;

            requestCards.forEach(card => {
                const searchText = card.getAttribute('data-search');
                const status = card.getAttribute('data-status');
                const priority = card.getAttribute('data-priority');

                const matchesSearch = searchText.includes(searchTerm);
                const matchesStatus = !statusValue || status === statusValue;
                const matchesPriority = !priorityValue || priority === priorityValue;

                if (matchesSearch && matchesStatus && matchesPriority) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show empty state if no cards are visible
            const emptyState = document.querySelector('.col-span-2.text-center');
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterRequests);
        if (statusFilter) statusFilter.addEventListener('change', filterRequests);
        if (priorityFilter) priorityFilter.addEventListener('change', filterRequests);

        // Initialize filters
        filterRequests();
    });
</script>
@endpush