@extends('layouts.admin')

@section('title', 'Maintenance - SmartRent')
@section('page-title', 'Maintenance')
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
                    <p class="text-3xl font-bold text-gray-900">{{ $totalRequests }}</p>
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
                    <p class="text-3xl font-bold text-gray-900">{{ $pendingRequests }}</p>
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
                    <p class="text-3xl font-bold text-gray-900">{{ $inProgressRequests }}</p>
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
                    <p class="text-3xl font-bold text-gray-900">{{ $highPriorityRequests }}</p>
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
                    <input type="text" id="searchInput" placeholder="Search requests..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <div class="ml-4 flex space-x-3">
                <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="approved">Approved</option>
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

    <!-- Maintenance Requests Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Request
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tenant & Owner
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Property
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Priority
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="maintenanceTableBody">
                    @forelse($maintenanceRequests as $request)
                    <tr class="hover:bg-gray-50" data-request-id="{{ $request->request_id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-wrench text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $request->title }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-xs">{{ $request->description }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">
                                    <i class="fas fa-user text-gray-400 mr-1 text-xs"></i>
                                    {{ $request->user->first_name ?? 'N/A' }} {{ $request->user->last_name ?? '' }} - Tenant
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-user-tie text-gray-400 mr-1 text-xs"></i>
                                    {{ $request->unit->property->landlord->first_name ?? 'N/A' }} 
                                    {{ $request->unit->property->landlord->last_name ?? '' }} - Landlord
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $request->unit->property->property_name ?? 'N/A' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                Unit {{ $request->unit->unit_num ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($request->status == 'pending') bg-orange-100 text-orange-800
                                @elseif($request->status == 'in_progress') bg-blue-100 text-blue-800
                                @elseif($request->status == 'approved') bg-green-100 text-green-800
                                @elseif($request->status == 'completed') bg-gray-100 text-gray-800
                                @elseif($request->status == 'cancelled') bg-red-100 text-red-800
                                @endif">
                                {{ str_replace('_', ' ', ucfirst($request->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($request->priority == 'high' || $request->priority == 'urgent') bg-red-100 text-red-800
                                @elseif($request->priority == 'medium') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($request->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $request->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if($request->status == 'pending')
                                <button onclick="approveRequest({{ $request->request_id }})" 
                                        class="text-green-600 hover:text-green-900 transition-colors"
                                        title="Approve and Create Billing">
                                    <i class="fas fa-check-circle w-5 h-5"></i>
                                </button>
                                @endif
                                
                                @if($request->status == 'pending' || $request->status == 'in_progress')
                                <button class="update-status-btn text-blue-600 hover:text-blue-900 transition-colors"
                                        data-request-id="{{ $request->request_id }}"
                                        title="Update Status">
                                    <i class="fas fa-sync-alt w-5 h-5"></i>
                                </button>
                                @endif
                                
                                @if($request->billing && $request->status == 'in_progress')
                                <button onclick="showPaymentModal({{ $request->request_id }})" 
                                        class="text-purple-600 hover:text-purple-900 transition-colors"
                                        title="Record Payment">
                                    <i class="fas fa-money-bill-wave w-5 h-5"></i>
                                </button>
                                @endif
                                
                                <button onclick="showDetailsModal({{ $request->request_id }})" 
                                        class="text-gray-600 hover:text-gray-900 transition-colors"
                                        title="View Details">
                                    <i class="fas fa-eye w-5 h-5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-wrench text-3xl mb-2"></i>
                                <p class="text-lg font-medium">No maintenance requests found</p>
                                <p class="text-sm">Create your first maintenance request</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
                    <p class="text-sm text-gray-500">Create a new maintenance request</p>
                </div>
            </div>
            <button onclick="closeNewRequestModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="maintenanceRequestForm" class="p-6" onsubmit="createNewRequest(event)">
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
                            Tenant <span class="text-red-500">*</span>
                        </label>
                        <select id="user_id" name="user_id" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Tenant</option>
                            @foreach($properties->flatMap->units->flatMap->leases->map->user->unique('user_id') as $tenant)
                                <option value="{{ $tenant->user_id }}">
                                    {{ $tenant->first_name }} {{ $tenant->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Property & Unit <span class="text-red-500">*</span>
                        </label>
                        <select id="unit_id" name="unit_id" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Unit</option>
                            @foreach($properties as $property)
                                <optgroup label="{{ $property->property_name }}">
                                    @foreach($property->units as $unit)
                                        <option value="{{ $unit->unit_id }}">
                                            Unit {{ $unit->unit_num }} - {{ $property->property_name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., AC Unit Not Working">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select id="priority" name="priority" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" name="description" required rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Provide detailed description of the issue..."></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <button type="button" onclick="closeNewRequestModal()" 
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

<!-- Update Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Request Status</h3>
            <form id="updateStatusForm" onsubmit="updateRequestStatus(event)">
                @csrf
                <input type="hidden" id="updateRequestId" name="request_id">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select id="status" name="status" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Assign to Staff
                        </label>
                        <select id="assigned_staff_id" name="assigned_staff_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Staff</option>
                            @foreach($staffUsers as $staff)
                                <option value="{{ $staff->user_id }}">
                                    {{ $staff->first_name }} {{ $staff->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeUpdateModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-xl font-bold text-gray-900">Request Details</h3>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="detailsContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Approve Cost Modal -->
<div id="approveCostModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Approve Request & Set Cost</h3>
            <form id="approveForm" onsubmit="confirmApproveRequest(event)">
                @csrf
                <input type="hidden" id="approveRequestId" name="request_id">
                
                <div class="space-y-4">
                    <!-- Staff Assignment -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Assign to Staff
                        </label>
                        <select id="assigned_staff_id" name="assigned_staff_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Staff Member</option>
                            @foreach($staffUsers as $staff)
                                <option value="{{ $staff->user_id }}">
                                    {{ $staff->first_name }} {{ $staff->last_name }}
                                    @if($staff->role == 'staff')
                                        (Staff)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Optional: Assign this request to a staff member</p>
                    </div>
                    
                    <!-- Maintenance Cost -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Maintenance Cost <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                            <input type="number" id="cost" name="cost" min="0" step="0.01" required
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0.00">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">This amount will be added to the tenant's billing</p>
                    </div>
                    
                    <!-- Due Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Due Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="due_date" name="due_date" required
                               value="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeApproveModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center space-x-2">
                        <i class="fas fa-check-circle text-sm"></i>
                        <span>Approve & Create Billing</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Invoice Modal -->
<div id="createInvoiceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Create Invoice for Maintenance</h3>
            <form id="createInvoiceForm" onsubmit="createInvoice(event)">
                @csrf
                <input type="hidden" id="invoiceRequestId" name="request_id">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Service Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                            <input type="number" id="invoiceAmount" name="amount" min="0" step="0.01" required
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0.00">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Late Fee
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                <input type="number" id="invoiceLateFee" name="late_fee" min="0" step="0.01" value="0"
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="0.00">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Overdue %
                            </label>
                            <div class="relative">
                                <input type="number" id="invoiceOverduePercent" name="overdue_amount_percent" min="0" max="100" step="0.01" value="0"
                                       class="w-full pl-3 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="0">
                                <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Due Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="invoiceDueDate" name="due_date" required
                               value="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Notes (Optional)
                        </label>
                        <textarea id="invoiceNotes" name="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Additional notes for the invoice..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeInvoiceModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Create Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Record Payment Modal -->
<div id="recordPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Record Payment</h3>
            <form id="recordPaymentForm" onsubmit="recordPayment(event)">
                @csrf
                <input type="hidden" id="paymentBillingId" name="billing_id">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Method <span class="text-red-500">*</span>
                        </label>
                        <select id="paymentMethod" name="payment_method" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Method</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="online">Online Payment</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Amount Paid <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                            <input type="number" id="paymentAmount" name="amount_paid" min="0" step="0.01" required
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0.00">
                        </div>
                        <div id="invoiceAmountDisplay" class="text-sm text-gray-500 mt-1"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Reference Number (Optional)
                        </label>
                        <input type="text" id="paymentReference" name="reference_number"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., Transaction ID, Check No.">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="paymentDate" name="payment_date" required
                               value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePaymentModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Status with Invoice Prompt Modal -->
<div id="updateStatusWithInvoiceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status to In Progress</h3>
            <div class="space-y-4">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                        <span class="text-sm text-yellow-800 font-medium">Invoice Required</span>
                    </div>
                    <p class="text-sm text-yellow-700 mt-2">
                        When changing status to "In Progress", you need to create an invoice for the maintenance service.
                    </p>
                </div>
                
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeStatusWithInvoiceModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="button" onclick="proceedToCreateInvoice()" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                        Create Invoice
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Modal functions
    function showNewRequestModal() {
        document.getElementById('newRequestModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeNewRequestModal() {
        document.getElementById('newRequestModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('maintenanceRequestForm').reset();
    }
    // Add these variables at the top
let currentRequestIdForInvoice = null;

// Update Status button click handler
document.querySelectorAll('.update-status-btn').forEach(button => {
    button.addEventListener('click', function() {
        const requestId = this.getAttribute('data-request-id');
        const currentStatus = this.closest('.maintenance-request-card').getAttribute('data-status');
        
        if (currentStatus === 'pending') {
            // Show invoice creation modal before changing to in_progress
            currentRequestIdForInvoice = requestId;
            document.getElementById('updateStatusWithInvoiceModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else if (currentStatus === 'in_progress') {
            // Show payment modal
            showPaymentModal(requestId);
        }
    });
});

// Invoice Creation Functions
function proceedToCreateInvoice() {
    closeStatusWithInvoiceModal();
    document.getElementById('invoiceRequestId').value = currentRequestIdForInvoice;
    document.getElementById('createInvoiceModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeInvoiceModal() {
    document.getElementById('createInvoiceModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('createInvoiceForm').reset();
    currentRequestIdForInvoice = null;
}

async function createInvoice(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const requestId = formData.get('request_id');
    
    try {
        const response = await fetch(`/admin/maintenance/${requestId}/invoice`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Invoice created successfully!');
            closeInvoiceModal();
            
            // Now update the status to in_progress
            await updateMaintenanceStatus(requestId, 'in_progress');
            location.reload();
        } else {
            alert('Error creating invoice: ' + data.message);
        }
    } catch (error) {
        alert('Error creating invoice. Please try again.');
    }
}

async function updateMaintenanceStatus(requestId, status) {
    try {
        const response = await fetch(`/admin/maintenance/${requestId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                status: status,
                _token: document.querySelector('meta[name="csrf-token"]').content
            })
        });
        
        const data = await response.json();
        return data.success;
    } catch (error) {
        console.error('Error updating status:', error);
        return false;
    }
}

    // Payment Functions
    function showPaymentModal(requestId) {
        // First get the invoice details
        fetch(`/admin/maintenance/${requestId}/invoice-details`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.invoice) {
                    document.getElementById('paymentBillingId').value = data.invoice.bill_id;
                    document.getElementById('invoiceAmountDisplay').innerHTML = 
                        `Invoice Amount: ₱${parseFloat(data.invoice.amount).toFixed(2)}`;
                    document.getElementById('recordPaymentModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                } else {
                    alert('No invoice found for this request. Please create an invoice first.');
                }
            });
    }

    function closePaymentModal() {
        document.getElementById('recordPaymentModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('recordPaymentForm').reset();
    }

    async function recordPayment(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const billingId = formData.get('billing_id');
        
        try {
            const response = await fetch(`/admin/billing/${billingId}/payment`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Payment recorded successfully! Maintenance status updated to completed.');
                closePaymentModal();
                location.reload();
            } else {
                alert('Error recording payment: ' + data.message);
            }
        } catch (error) {
            alert('Error recording payment. Please try again.');
        }
    }

    // Close modals
    function closeStatusWithInvoiceModal() {
        document.getElementById('updateStatusWithInvoiceModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        currentRequestIdForInvoice = null;
    }

    // Add this to your existing modal close event listeners
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });
    function showDetailsModal(requestId) {
    fetch(`/admin/maintenance/${requestId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const request = data.request;
                
                // Format assigned staff information
                let assignedStaffHtml = '';
                if (request.assigned_staff) {
                    assignedStaffHtml = `
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Assigned Staff</h4>
                            <div class="flex items-center mt-2">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user-cog text-blue-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        ${request.assigned_staff.first_name} ${request.assigned_staff.last_name}
                                    </p>
                                    <p class="text-xs text-gray-500">${request.assigned_staff.email || 'No email'}</p>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    assignedStaffHtml = `
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Assigned Staff</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="text-orange-600">No staff assigned yet</span>
                            </p>
                        </div>
                    `;
                }
                
                let detailsHtml = `
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Request Title</h4>
                                <p class="text-lg font-semibold text-gray-900">${request.title}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Priority</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    ${request.priority === 'high' || request.priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                      request.priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'}">
                                    ${request.priority.charAt(0).toUpperCase() + request.priority.slice(1)}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Description</h4>
                            <p class="text-gray-700 mt-1">${request.description}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Tenant</h4>
                                <div class="flex items-center mt-1">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-green-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            ${request.user.first_name} ${request.user.last_name}
                                        </p>
                                        <p class="text-xs text-gray-500">Tenant</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Property Owner</h4>
                                <div class="flex items-center mt-1">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user-tie text-purple-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            ${request.unit.property.landlord.first_name} ${request.unit.property.landlord.last_name}
                                        </p>
                                        <p class="text-xs text-gray-500">Landlord</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Property</h4>
                                <p class="text-gray-700">${request.unit.property.property_name}</p>
                                <p class="text-sm text-gray-500">Unit ${request.unit.unit_num}</p>
                                <p class="text-xs text-gray-400">${request.unit.property.property_address || 'No address'}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    ${request.status === 'pending' ? 'bg-orange-100 text-orange-800' :
                                      request.status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                      request.status === 'approved' ? 'bg-green-100 text-green-800' :
                                      request.status === 'completed' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800'}">
                                    ${request.status.replace('_', ' ').charAt(0).toUpperCase() + request.status.replace('_', ' ').slice(1)}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Assigned Staff Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${assignedStaffHtml}
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Request Date</h4>
                                <p class="text-sm text-gray-700 mt-1">
                                    <i class="far fa-calendar-alt text-gray-400 mr-2"></i>
                                    ${new Date(request.created_at).toLocaleDateString('en-US', { 
                                        weekday: 'long', 
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric' 
                                    })}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    ${new Date(request.created_at).toLocaleTimeString()}
                                </p>
                            </div>
                        </div>
                        
                        ${request.billing ? `
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-green-800">Billing Information</h4>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    ${request.billing.status}
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-green-700 font-medium">Billing ID</p>
                                    <p class="text-sm text-green-900">${request.billing.bill_id}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-green-700 font-medium">Amount</p>
                                    <p class="text-sm text-green-900 font-bold">₱${parseFloat(request.billing.amount).toFixed(2)}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-green-700 font-medium">Due Date</p>
                                    <p class="text-sm text-green-900">${new Date(request.billing.due_date).toLocaleDateString()}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-green-700 font-medium">Bill Name</p>
                                    <p class="text-sm text-green-900 truncate">${request.billing.bill_name}</p>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                        
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-3">Request Timeline</h4>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-calendar-plus text-blue-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Request Submitted</p>
                                        <p class="text-xs text-gray-500">${new Date(request.created_at).toLocaleString()}</p>
                                    </div>
                                </div>
                                
                                ${request.assigned_at ? `
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-user-cog text-purple-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Staff Assigned</p>
                                        <p class="text-xs text-gray-500">${new Date(request.assigned_at).toLocaleString()}</p>
                                        ${request.assigned_staff ? `
                                        <p class="text-xs text-gray-600 mt-1">
                                            Assigned to: ${request.assigned_staff.first_name} ${request.assigned_staff.last_name}
                                        </p>
                                        ` : ''}
                                    </div>
                                </div>
                                ` : ''}
                                
                                ${request.approved_at ? `
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Request Approved</p>
                                        <p class="text-xs text-gray-500">${new Date(request.approved_at).toLocaleString()}</p>
                                    </div>
                                </div>
                                ` : ''}
                                
                                ${request.completed_at ? `
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-flag-checkered text-gray-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Request Completed</p>
                                        <p class="text-xs text-gray-500">${new Date(request.completed_at).toLocaleString()}</p>
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('detailsContent').innerHTML = detailsHtml;
                document.getElementById('detailsModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        });
}

    function closeDetailsModal() {
        document.getElementById('detailsModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function approveRequest(requestId) {
        document.getElementById('approveRequestId').value = requestId;
        document.getElementById('approveCostModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeApproveModal() {
        document.getElementById('approveCostModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('approveForm').reset();
    }

    // Event Listeners
    document.getElementById('newRequestBtn').addEventListener('click', showNewRequestModal);

    // Create new request
    async function createNewRequest(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        
        try {
            const response = await fetch('/admin/maintenance', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Maintenance request created successfully!');
                closeNewRequestModal();
                location.reload(); // Reload to show new request
            } else {
                alert('Error creating request: ' + data.message);
            }
        } catch (error) {
            alert('Error creating request. Please try again.');
        }
    }

    // Update request status
    async function updateRequestStatus(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        
        try {
            const response = await fetch(`/admin/maintenance/${formData.get('request_id')}/status`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Status updated successfully!');
                closeUpdateModal();
                location.reload();
            }
        } catch (error) {
            alert('Error updating status. Please try again.');
        }
    }

    // Approve request and create billing
    async function confirmApproveRequest(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const requestId = formData.get('request_id');
        
        try {
            const response = await fetch(`/admin/maintenance/${requestId}/approve`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Request approved' + (data.billing_id ? ' and billing created!' : '!'));
                closeApproveModal();
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            alert('Error approving request. Please try again.');
        }
    }

    // Search and filter functionality
    document.getElementById('searchInput').addEventListener('input', filterRequests);
    document.getElementById('statusFilter').addEventListener('change', filterRequests);
    document.getElementById('priorityFilter').addEventListener('change', filterRequests);

    function filterRequests() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const priorityFilter = document.getElementById('priorityFilter').value;
        
        const rows = document.querySelectorAll('#maintenanceTableBody tr');
        
        rows.forEach(row => {
            const title = row.querySelector('td:nth-child(1) .text-sm').textContent.toLowerCase();
            const tenant = row.querySelector('td:nth-child(2) .font-medium').textContent.toLowerCase();
            const property = row.querySelector('td:nth-child(3) .text-sm').textContent.toLowerCase();
            const status = row.querySelector('td:nth-child(4) span').textContent.toLowerCase();
            const priority = row.querySelector('td:nth-child(5) span').textContent.toLowerCase();
            
            const matchesSearch = title.includes(searchTerm) || 
                                 tenant.includes(searchTerm) || 
                                 property.includes(searchTerm);
            const matchesStatus = !statusFilter || status.includes(statusFilter);
            const matchesPriority = !priorityFilter || priority.includes(priorityFilter);
            
            row.style.display = matchesSearch && matchesStatus && matchesPriority ? '' : 'none';
        });
    }
</script>
@endpush