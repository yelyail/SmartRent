@extends('layouts.staff')

@section('title', 'Dashboard - SmartRent')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome back! Here\'s your maintenance overview.')

@section('content')
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-sm p-6 mb-8 text-white">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">Welcome back, {{ $user->first_name }}!</h1>
                <p class="text-blue-100">Here's your maintenance overview for today</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="text-right">
                    <p class="text-sm text-blue-200">Today's Date</p>
                    <p class="text-xl font-semibold">{{ now()->format('F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Assigned Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tasks text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Assigned</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $maintenanceStats['total_assigned'] }}</p>
                </div>
            </div>
        </div>

        <!-- In Progress Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-sync-alt text-orange-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">In Progress</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $maintenanceStats['in_progress'] }}</p>
                </div>
            </div>
        </div>

        <!-- Completed This Month -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Completed This Month</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $maintenanceStats['completed_this_month'] }}</p>
                </div>
            </div>
        </div>

        <!-- Urgent Pending -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Urgent Pending</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $maintenanceStats['urgent_pending'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Urgent Requests -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Urgent Maintenance Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Urgent Maintenance Requests</h3>
                    <a href="{{ route('staff.maintenance') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        View All
                    </a>
                </div>
                
                @if($urgentRequests->count() > 0)
                    <div class="space-y-4">
                        @foreach($urgentRequests as $request)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    @if($request->priority == 'urgent')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            URGENT
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            HIGH
                                        </span>
                                    @endif
                                    <span class="text-sm text-gray-500">
                                        #MR-{{ str_pad($request->request_id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500">
                                    {{ $request->days_open }} day{{ $request->days_open > 1 ? 's' : '' }}
                                </span>
                            </div>
                            
                            <h4 class="font-medium text-gray-900 mb-1">{{ $request->title }}</h4>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($request->description, 100) }}</p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-600">
                                        <i class="fas fa-home mr-1"></i>
                                        {{ $request->property_name }} - Unit {{ $request->unit_number }}
                                    </span>
                                </div>
                                <a href="{{ route('staff.maintenance') }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    Take Action
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check text-green-600 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No Urgent Requests</h4>
                        <p class="text-gray-600">Great job! You have no urgent maintenance requests.</p>
                    </div>
                @endif
            </div>

            <!-- Upcoming Tasks -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Upcoming Tasks</h3>
                    <a href="{{ route('staff.maintenance') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        View All
                    </a>
                </div>
                
                @if($upcomingTasks->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingTasks as $request)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-wrench text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $request->title }}</h4>
                                    <p class="text-sm text-gray-500">
                                        {{ $request->property_name }} - Unit {{ $request->unit_number }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">{{ $request->tenant_name }}</p>
                                <p class="text-xs text-gray-500">
                                    Requested {{ $request->requested_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No Upcoming Tasks</h4>
                        <p class="text-gray-600">You're all caught up! Check back later for new tasks.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-8">
            <!-- Assigned Properties -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Assigned Properties</h3>
                
                @if($assignedProperties->count() > 0)
                    <div class="space-y-6">
                        @foreach($assignedProperties as $property)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-3">
                                <h4 class="font-medium text-gray-900">{{ $property['property_name'] }}</h4>
                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                    {{ $property['active_requests'] }} active
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($property['address'], 40) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    @if($property['urgent_requests'] > 0)
                                        <span class="text-red-600 font-medium">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $property['urgent_requests'] }} urgent
                                        </span>
                                    @else
                                        <span class="text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            All clear
                                        </span>
                                    @endif
                                </span>
                                <a href="#" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    View Details
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-building text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No Assigned Properties</h4>
                        <p class="text-gray-600">You haven't been assigned to any properties yet.</p>
                    </div>
                @endif
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Activities</h3>
                
                @if($recentActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1
                                @if($activity['icon_color'] == 'red') bg-red-100 text-red-600
                                @elseif($activity['icon_color'] == 'orange') bg-orange-100 text-orange-600
                                @elseif($activity['icon_color'] == 'green') bg-green-100 text-green-600
                                @elseif($activity['icon_color'] == 'blue') bg-blue-100 text-blue-600
                                @else bg-yellow-100 text-yellow-600 @endif">
                                <i class="{{ $activity['icon'] }} text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $activity['title'] }}</h4>
                                <p class="text-sm text-gray-500">{{ $activity['property'] }} - Unit {{ $activity['unit'] }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $activity['updated_at']->diffForHumans() }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full
                                @if($activity['status'] == 'completed') bg-green-100 text-green-800
                                @elseif($activity['status'] == 'in_progress') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ strtoupper($activity['status']) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-history text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No Recent Activities</h4>
                        <p class="text-gray-600">Your activity log will appear here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('modals')
<!-- New Task Modal -->
<div id="newTaskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Quick New Task</h2>
                <button onclick="closeNewTaskModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form id="newTaskForm" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Task Title
                </label>
                <input type="text" name="title" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Enter task title">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Priority
                </label>
                <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Quick Notes
                </label>
                <textarea name="notes" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Brief description..."></textarea>
            </div>
            
            <div class="flex items-center justify-end space-x-3">
                <button type="button" onclick="closeNewTaskModal()" 
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Create Task
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // New Task Modal Functions
    function startNewTask() {
        document.getElementById('newTaskModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeNewTaskModal() {
        document.getElementById('newTaskModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('newTaskForm').reset();
    }
    
    // Handle new task form submission
    document.getElementById('newTaskForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating...';
        submitBtn.disabled = true;
        
        // Here you would typically send an AJAX request
        setTimeout(() => {
            alert('Task created successfully! Redirecting to maintenance page...');
            closeNewTaskModal();
            window.location.href = "{{ route('staff.maintenance') }}";
        }, 1000);
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const newTaskModal = document.getElementById('newTaskModal');
            if (!newTaskModal.classList.contains('hidden')) {
                closeNewTaskModal();
            }
        }
    });
    
    // Auto-refresh dashboard every 5 minutes
    setInterval(() => {
        console.log('Auto-refreshing dashboard...');
    }, 300000); // 5 minutes
</script>
@endpush