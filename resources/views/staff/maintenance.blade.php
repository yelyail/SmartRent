@extends('layouts.staff')

@section('title', 'Maintenance - SmartRent')
@section('page-title', 'My Maintenance Tasks')
@section('page-description', 'Track and manage your assigned maintenance requests.')
@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- My In Progress Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tools text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">My Tasks</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_tasks'] }}</p>
                </div>                           
            </div>
        </div>

        <!-- High Priority Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">High Priority</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ $stats['high_priority'] }}
                    </p>
                </div>                          
            </div>
        </div>

        <!-- Days Open -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Avg Days Open</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ round($stats['avg_days_open']) }}
                    </p>
                </div>                           
            </div>
        </div>

        <!-- Urgent Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-purple-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Urgent</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ $maintenanceRequests->where('priority', 'urgent')->count() }}
                    </p>
                </div>                           
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Search my tasks..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <div class="ml-4">
                <select id="priorityFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all">All Priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Maintenance Requests Grid -->
    @if($maintenanceRequests->count() > 0)
        <div id="maintenanceGrid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($maintenanceRequests as $request)
            <div class="maintenance-card bg-white rounded-xl shadow-sm border border-gray-200 p-6" 
                 data-priority="{{ $request->priority }}" 
                 data-search="{{ strtolower($request->title . ' ' . $request->description . ' ' . $request->property_name . ' ' . $request->tenant_name) }}">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex space-x-2">
                        <!-- Priority Badge -->
                        @if($request->priority == 'urgent')
                            <span class="priority-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                URGENT
                            </span>
                        @elseif($request->priority == 'high')
                            <span class="priority-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                HIGH
                            </span>
                        @elseif($request->priority == 'medium')
                            <span class="priority-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                MEDIUM
                            </span>
                        @else
                            <span class="priority-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                LOW
                            </span>
                        @endif

                        <!-- Status Badge -->
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            IN PROGRESS
                        </span>
                    </div>
                    <div class="text-xs text-gray-500">
                        #MR-{{ str_pad($request->request_id, 5, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $request->title }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ $request->description }}</p>
                
                <div class="space-y-3 mb-6">
                    <!-- Property Information -->
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-home w-4 mr-3"></i>
                        <span class="flex-1 property-info">
                            {{ $request->property_name }} - Unit {{ $request->unit_number }}
                        </span>
                    </div>
                    
                    <!-- Tenant Information -->
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-user w-4 mr-3"></i>
                        <span class="flex-1 tenant-info">
                            Tenant: <span class="font-medium">{{ $request->tenant_name }}</span>
                        </span>
                    </div>
                    
                    <!-- Dates -->
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar w-4 mr-3"></i>
                        <span class="flex-1">
                            Requested: {{ $request->requested_at->format('M d, Y') }}
                            @if($request->days_open > 0)
                                <span class="ml-2 {{ $request->days_open > 7 ? 'text-red-600' : 'text-orange-600' }} font-medium">
                                    ({{ $request->days_open }} day{{ $request->days_open > 1 ? 's' : '' }})
                                </span>
                            @endif
                        </span>
                    </div>
                    
                    <!-- Assigned Information -->
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-user-cog w-4 mr-3"></i>
                        <span class="flex-1">
                            Assigned to: <span class="font-medium">{{ $request->assigned_staff_name }}</span>
                        </span>
                    </div>
                </div>
                
                <div class="flex space-x-3">
                    <button onclick="updateStatus({{ $request->request_id }})" 
                            class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-sync-alt text-sm"></i>
                        <span>Update Status</span>
                    </button>
                    <button onclick="viewDetails({{ $request->request_id }})" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex items-center justify-center">
                        <i class="fas fa-eye mr-2"></i>
                        View Details
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Active Tasks</h3>
                <p class="text-gray-600 mb-6">You don't have any maintenance tasks in progress at the moment.</p>
                <div class="space-x-3">
                    <button onclick="location.reload()" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors inline-flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh
                    </button>
                    <a href="{{ route('staff.dashboard') }}" 
                       class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('modals')
<!-- Update Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Update Task Status</h2>
                <button id="closeUpdateModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <p class="text-sm text-gray-500 mt-1">Request #<span id="currentRequestId"></span></p>
        </div>
        
        <form id="updateStatusForm" method="POST" class="p-6">
            @csrf
            <input type="hidden" id="requestId" name="request_id">
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    New Status
                </label>
                <select id="newStatus" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="in_progress" selected>In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            
            
            <div class="flex items-center justify-end space-x-3">
                <button type="button" id="cancelUpdateBtn" 
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Details Modal -->
<div id="viewDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Maintenance Request Details</h2>
                <button id="closeDetailsModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <p class="text-sm text-gray-500 mt-1">Request #<span id="detailsRequestId"></span></p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2" id="detailsTitle"></h3>
                        <p class="text-gray-600" id="detailsDescription"></p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-3">Property Information</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Property:</span>
                                <span class="font-medium" id="detailsProperty"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Unit:</span>
                                <span class="font-medium" id="detailsUnit"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tenant:</span>
                                <span class="font-medium" id="detailsTenant"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-3">Request Details</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Priority:</span>
                                <span class="font-medium" id="detailsPriority"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium" id="detailsStatus"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Requested:</span>
                                <span class="font-medium" id="detailsRequestedDate"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Days Open:</span>
                                <span class="font-medium" id="detailsDaysOpen"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Assigned to:</span>
                                <span class="font-medium" id="detailsAssignedTo"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-3">Contact Information</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tenant Phone:</span>
                                <span class="font-medium" id="detailsTenantPhone"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tenant Email:</span>
                                <span class="font-medium" id="detailsTenantEmail"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <button id="closeDetailsBtn" 
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Close
                </button>
                <button onclick="updateStatusFromDetails()" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Update Status
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    let currentRequestId = null;
    
    // Update Status Function
    function updateStatus(requestId) {
        currentRequestId = requestId;
        
        // Set the request ID in the form
        document.getElementById('currentRequestId').textContent = requestId;
        document.getElementById('requestId').value = requestId;
        
        // Set the form action
        document.getElementById('updateStatusForm').action = `/maintenance/${requestId}/update-status`;
        
        // Show modal
        document.getElementById('updateStatusModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    // View Details Function
    function viewDetails(requestId) {
        // Find the card with this request ID
        const card = document.querySelector(`.maintenance-card[data-search*="${requestId}"]`);
        if (!card) return;
        
        // Extract data from the card
        const title = card.querySelector('h3').textContent;
        const description = card.querySelector('p.text-gray-600').textContent;
        const propertyInfo = card.querySelector('.property-info').textContent;
        const tenantInfo = card.querySelector('.tenant-info').textContent;
        const priority = card.querySelector('.priority-badge').textContent.trim();
        const requestedDate = card.querySelector('.fa-calendar + span').textContent.split('Requested: ')[1].split(' (')[0];
        const daysOpen = card.querySelector('.fa-calendar + span .font-medium')?.textContent || '0 days';
        const assignedTo = card.querySelector('.fa-user-cog + span').textContent.replace('Assigned to: ', '');
        
        // Set data in modal
        document.getElementById('detailsRequestId').textContent = requestId;
        document.getElementById('detailsTitle').textContent = title;
        document.getElementById('detailsDescription').textContent = description;
        document.getElementById('detailsProperty').textContent = propertyInfo.split(' - ')[0];
        document.getElementById('detailsUnit').textContent = propertyInfo.split('Unit ')[1] || 'N/A';
        document.getElementById('detailsTenant').textContent = tenantInfo.replace('Tenant: ', '');
        document.getElementById('detailsPriority').textContent = priority;
        document.getElementById('detailsStatus').textContent = 'IN PROGRESS';
        document.getElementById('detailsRequestedDate').textContent = requestedDate;
        document.getElementById('detailsDaysOpen').textContent = daysOpen;
        document.getElementById('detailsAssignedTo').textContent = assignedTo;
        
        // Store request ID for update button
        currentRequestId = requestId;
        
        // Show modal
        document.getElementById('viewDetailsModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    // Update Status from Details Modal
    function updateStatusFromDetails() {
        closeDetailsModal();
        if (currentRequestId) {
            updateStatus(currentRequestId);
        }
    }
    
    // Update Status Modal functionality
    const updateModal = document.getElementById('updateStatusModal');
    const closeUpdateModalBtn = document.getElementById('closeUpdateModalBtn');
    const cancelUpdateBtn = document.getElementById('cancelUpdateBtn');
    
    // View Details Modal functionality
    const detailsModal = document.getElementById('viewDetailsModal');
    const closeDetailsModalBtn = document.getElementById('closeDetailsModalBtn');
    const closeDetailsBtn = document.getElementById('closeDetailsBtn');
    
    // Close update modal
    function closeUpdateModal() {
        updateModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        currentRequestId = null;
    }
    
    // Close details modal
    function closeDetailsModal() {
        detailsModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Event listeners for update modal
    closeUpdateModalBtn.addEventListener('click', closeUpdateModal);
    cancelUpdateBtn.addEventListener('click', closeUpdateModal);
    
    // Event listeners for details modal
    closeDetailsModalBtn.addEventListener('click', closeDetailsModal);
    closeDetailsBtn.addEventListener('click', closeDetailsModal);
    
    // Close modals when clicking outside
    updateModal.addEventListener('click', (e) => {
        if (e.target === updateModal) {
            closeUpdateModal();
        }
    });
    
    detailsModal.addEventListener('click', (e) => {
        if (e.target === detailsModal) {
            closeDetailsModal();
        }
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (!updateModal.classList.contains('hidden')) {
                closeUpdateModal();
            }
            if (!detailsModal.classList.contains('hidden')) {
                closeDetailsModal();
            }
        }
    });
    
    // Form validation for update status
    document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
        const newStatus = document.getElementById('newStatus').value;
        const completionNotes = document.getElementById('completionNotes').value;
        
        if (newStatus === 'completed' && !completionNotes.trim()) {
            e.preventDefault();
            alert('Please provide completion notes when marking a task as completed.');
            document.getElementById('completionNotes').focus();
            return;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
        submitBtn.disabled = true;
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.maintenance-card');
            const priorityFilter = document.getElementById('priorityFilter');
            const selectedPriority = priorityFilter ? priorityFilter.value : 'all';
            
            cards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                const cardPriority = card.getAttribute('data-priority');
                
                // Check search term
                const matchesSearch = !searchTerm || searchData.includes(searchTerm);
                
                // Check priority filter
                const matchesPriority = selectedPriority === 'all' || cardPriority === selectedPriority;
                
                // Show/hide card based on both filters
                if (matchesSearch && matchesPriority) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show/hide empty state
            const visibleCards = Array.from(cards).filter(card => card.style.display !== 'none');
            const grid = document.getElementById('maintenanceGrid');
            const emptyState = document.querySelector('.bg-white.rounded-xl.shadow-sm.border.p-12.text-center');
            
            if (visibleCards.length === 0 && grid) {
                if (!emptyState) {
                    grid.insertAdjacentHTML('afterend', `
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                            <div class="max-w-md mx-auto">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-search text-blue-600 text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Tasks Found</h3>
                                <p class="text-gray-600 mb-6">No maintenance tasks match your search criteria.</p>
                                <button onclick="clearFilters()" 
                                        class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors inline-flex items-center">
                                    <i class="fas fa-times mr-2"></i>
                                    Clear Filters
                                </button>
                            </div>
                        </div>
                    `);
                    grid.style.display = 'none';
                }
            } else {
                if (emptyState) {
                    emptyState.remove();
                    grid.style.display = 'grid';
                }
            }
        });
    }
    
    // Priority filter functionality
    const priorityFilter = document.getElementById('priorityFilter');
    if (priorityFilter) {
        priorityFilter.addEventListener('change', function(e) {
            // Trigger the search input event to update both filters
            if (searchInput) {
                searchInput.dispatchEvent(new Event('input'));
            } else {
                // Fallback if search input doesn't exist
                const selectedPriority = e.target.value;
                const cards = document.querySelectorAll('.maintenance-card');
                
                cards.forEach(card => {
                    const cardPriority = card.getAttribute('data-priority');
                    
                    if (selectedPriority === 'all' || cardPriority === selectedPriority) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
        });
    }
    
    // Clear filters function
    function clearFilters() {
        if (searchInput) {
            searchInput.value = '';
        }
        if (priorityFilter) {
            priorityFilter.value = 'all';
        }
        
        // Trigger the search input event to update display
        if (searchInput) {
            searchInput.dispatchEvent(new Event('input'));
        } else if (priorityFilter) {
            priorityFilter.dispatchEvent(new Event('change'));
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set up the search data attribute for each card if not already set
        document.querySelectorAll('.maintenance-card').forEach(card => {
            if (!card.hasAttribute('data-search')) {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p.text-gray-600').textContent.toLowerCase();
                const property = card.querySelector('.property-info').textContent.toLowerCase();
                const tenant = card.querySelector('.tenant-info').textContent.toLowerCase();
                const searchData = `${title} ${description} ${property} ${tenant}`;
                card.setAttribute('data-search', searchData);
            }
        });
    });
</script>
@endpush