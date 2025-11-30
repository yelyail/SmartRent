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

        <!-- High Priority -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">High Priority</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $maintenanceRequests->where('priority', 'high')->count() + $maintenanceRequests->where('priority', 'urgent')->count() }}</p>
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
                                {{ $request->unit->property->property_name }} - {{ $request->unit->unit_name }} #{{ $request->unit->unit_num }}
                            @else
                                Unit Not Available
                            @endif
                        </span>
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
                    @if($request->status == 'pending')
                        <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2 update-status-btn" data-request-id="{{ $request->request_id }}">
                            <i class="fas fa-sync-alt text-sm"></i>
                            <span>Update Status</span>
                        </button>
                    @elseif($request->status == 'in_progress')
                        <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2 update-status-btn" data-request-id="{{ $request->request_id }}">
                            <i class="fas fa-clock text-sm"></i>
                            <span>Update Status</span>
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
<!-- New Request Modal -->
<div id="newRequestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wrench text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">New Maintenance Request</h2>
                    <p class="text-sm text-gray-500">Submit a new maintenance request</p>
                </div>
            </div>
            <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="maintenanceRequestForm" class="p-6">
            @csrf
            <!-- Request Information -->
            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Request Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="requestTitle" name="title" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., AC Unit Not Working" maxlength="150">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" name="description" required rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Please provide a detailed description of the issue..."></textarea>
                </div>

                <!-- Auto-detected Priority Display -->
                <div id="priorityDisplay" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Detected Priority Level
                    </label>
                    <div id="priorityBadge" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                    </div>
                    <p id="priorityDescription" class="text-sm text-gray-600 mt-1"></p>
                </div>

                <!-- Unit Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Unit <span class="text-red-500">*</span>
                    </label>
                    <select id="unit_id" name="unit_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Your Unit</option>
                        @foreach($userUnits as $unit)
                            <option value="{{ $unit->unit_id }}">
                                {{ $unit->property->property_name ?? 'Property' }} - Unit #{{ $unit->unit_num ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Staff Assignment -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Assign to Staff (Optional)
                    </label>
                    <select id="assigned_staff_id" name="assigned_staff_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Staff Member</option>
                        @foreach($staffMembers as $staff)
                            <option value="{{ $staff->user_id }}">
                                {{ $staff->first_name }} {{ $staff->last_name }} - {{ $staff->position ?? 'Staff' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Leave blank to let the system assign the appropriate staff based on the issue type.
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                <button type="button" id="cancelBtn" 
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-plus text-sm"></i>
                    <span>Submit Request</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Search and Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const priorityFilter = document.getElementById('priorityFilter');
        const requestCards = document.querySelectorAll('.maintenance-request-card');
        const newRequestEmptyBtn = document.getElementById('newRequestEmptyBtn');

        // Filter requests function
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
            const emptyState = document.querySelector('.col-span-2.bg-white');
            if (emptyState) {
                if (visibleCount === 0 && requestCards.length > 0) {
                    emptyState.style.display = 'block';
                } else {
                    emptyState.style.display = 'none';
                }
            }
        }

        // Event listeners for filters
        if (searchInput) searchInput.addEventListener('input', filterRequests);
        if (statusFilter) statusFilter.addEventListener('change', filterRequests);
        if (priorityFilter) priorityFilter.addEventListener('change', filterRequests);

        // Empty state button
        if (newRequestEmptyBtn) {
            newRequestEmptyBtn.addEventListener('click', () => {
                const modal = document.getElementById('newRequestModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            });
        }

        // View details button functionality
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', function() {
                const requestId = this.getAttribute('data-request-id');
                // Implement view details functionality
                alert('View details for request: ' + requestId);
                // You can redirect to a details page or show a modal
            });
        });

        // Update status button functionality
        document.querySelectorAll('.update-status-btn').forEach(button => {
            button.addEventListener('click', function() {
                const requestId = this.getAttribute('data-request-id');
                // Implement status update functionality
                alert('Update status for request: ' + requestId);
                // You can show a modal to update status
            });
        });

        // Initialize filters on page load
        filterRequests();
    });

    // Modal functionality
    const modal = document.getElementById('newRequestModal');
    const newRequestBtn = document.getElementById('newRequestBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const form = document.getElementById('maintenanceRequestForm');

    // Open modal
    if (newRequestBtn) {
        newRequestBtn.addEventListener('click', () => {
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        });
    }

    // Close modal functions
    function closeModal() {
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            if (form) form.reset();
            
            // Hide priority display
            const priorityDisplay = document.getElementById('priorityDisplay');
            if (priorityDisplay) priorityDisplay.classList.add('hidden');
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
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Real-time priority detection
    const titleInput = document.getElementById('requestTitle');
    const descriptionInput = document.getElementById('description');
    const priorityDisplay = document.getElementById('priorityDisplay');
    const priorityBadge = document.getElementById('priorityBadge');
    const priorityDescription = document.getElementById('priorityDescription');

    function detectPriority() {
        if (!titleInput || !descriptionInput || !priorityDisplay || !priorityBadge || !priorityDescription) return;

        const title = titleInput.value.toLowerCase();
        const description = descriptionInput.value.toLowerCase();
        const content = title + ' ' + description;

        if (!title && !description) {
            priorityDisplay.classList.add('hidden');
            return;
        }

        // Simple client-side priority detection
        const emergencyKeywords = ['fire', 'flood', 'gas leak', 'carbon monoxide', 'electrical fire', 'sparking', 'smoke'];
        const highPriorityKeywords = ['leak', 'leaking', 'water damage', 'no ac', 'no heat', 'hot water', 'toilet not working', 'clogged'];
        const mediumPriorityKeywords = ['dripping', 'slow drain', 'light not working', 'bulb replacement', 'painting'];
        const lowPriorityKeywords = ['touch up', 'cosmetic', 'paint chip', 'small crack', 'minor scratch'];

        let detectedPriority = 'medium';
        let descriptionText = 'Standard maintenance request';

        if (emergencyKeywords.some(keyword => content.includes(keyword))) {
            detectedPriority = 'urgent';
            descriptionText = 'Emergency issue requiring immediate attention';
        } else if (highPriorityKeywords.some(keyword => content.includes(keyword))) {
            detectedPriority = 'high';
            descriptionText = 'Urgent issue that should be addressed quickly';
        } else if (mediumPriorityKeywords.some(keyword => content.includes(keyword))) {
            detectedPriority = 'medium';
            descriptionText = 'Standard maintenance request';
        } else if (lowPriorityKeywords.some(keyword => content.includes(keyword))) {
            detectedPriority = 'low';
            descriptionText = 'Low priority cosmetic issue';
        }

        // Update UI
        const priorityClasses = {
            'urgent': 'bg-red-100 text-red-800',
            'high': 'bg-orange-100 text-orange-800',
            'medium': 'bg-yellow-100 text-yellow-800',
            'low': 'bg-green-100 text-green-800'
        };

        priorityBadge.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${priorityClasses[detectedPriority]}`;
        priorityBadge.textContent = detectedPriority.toUpperCase();
        priorityDescription.textContent = descriptionText;
        priorityDisplay.classList.remove('hidden');
    }

    // Add event listeners for real-time detection
    if (titleInput) titleInput.addEventListener('input', detectPriority);
    if (descriptionInput) descriptionInput.addEventListener('input', detectPriority);

    // Form submission
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i><span>Submitting...</span>';
            submitBtn.disabled = true;

            try {
                const formData = new FormData(form);
                
                const response = await fetch('{{ route("tenants.maintenance-requests.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    showNotification('Maintenance request submitted successfully!', 'success');
                    closeModal();
                    
                    // Reload the page to show the new request
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(result.message || 'Failed to submit maintenance request');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification(error.message || 'An error occurred while submitting the request', 'error');
            } finally {
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }

    function showNotification(message, type = 'info') {
        // Create notification element
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
        
        // Remove notification after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }
</script>
@endpush