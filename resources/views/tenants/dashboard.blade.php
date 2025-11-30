@extends('layouts.tenants')

@section('title', 'Dashboard_tenant - SmartRent')
@section('page-description', 'Welcome back! Here\'s what\'s happening with your rental.')

@section('content')
    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Current Lease -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-home text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Current Unit</p>
                    <p class="text-2xl font-bold text-gray-900 mb-1">
                        @if($currentLease)
                            {{ $currentLease->unit->unit_name ?? 'Unit' }} #{{ $currentLease->unit->unit_num }}
                        @else
                            No Active Lease
                        @endif
                    </p>
                    <p class="text-sm text-gray-600">
                        @if($currentLease)
                            {{ $currentLease->property->property_name }}
                        @else
                            No property assigned
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Rent Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 {{ $rentStatus['color'] }} rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Rent Status</p>
                    <p class="text-2xl font-bold {{ $rentStatus['textColor'] }} mb-1">{{ $rentStatus['status'] }}</p>
                    <p class="text-sm text-gray-600">Due: {{ $rentStatus['dueDate'] }}</p>
                </div>
            </div>
        </div>

        <!-- Maintenance Requests -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wrench text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Maintenance</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $maintenanceStats['total'] }}</p>
                    <p class="text-sm {{ $maintenanceStats['pending'] > 0 ? 'text-orange-600' : 'text-green-600' }}">
                        {{ $maintenanceStats['pending'] }} pending
                    </p>
                </div>
            </div>
        </div>

        <!-- Smart Devices -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-mobile-alt text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Smart Devices</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $deviceStats['total'] }}</p>
                    <p class="text-sm {{ $deviceStats['online'] == $deviceStats['total'] ? 'text-green-600' : 'text-orange-600' }}">
                        {{ $deviceStats['online'] }}/{{ $deviceStats['total'] }} online
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activities -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Activities</h3>
                <a href="{{ route('tenants.analytics') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="space-y-6">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 {{ $activity['color'] }} rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="{{ $activity['icon'] }} text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $activity['title'] }}</h4>
                            <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                            @if(isset($activity['payment_date']))
                                <p class="text-xs text-gray-500">Date: {{ $activity['payment_date'] }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                        </div>
                        @if(isset($activity['amount_paid']))
                            <div class="text-right">
                                <p class="font-medium text-gray-900">{{ $activity['amount_paid'] }}</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $activity['statusColor'] }}">
                                    {{ $activity['status'] }}
                                </span>
                            </div>
                        @else
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $activity['statusColor'] }}">
                                    {{ $activity['status'] }}
                                </span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">No recent activities</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions & Overview -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('tenants.analytics') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-2">
                            <i class="fas fa-credit-card text-green-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Pay Rent</span>
                    </a>
                    
                    <a href="{{ route('tenants.maintenance') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-2">
                            <i class="fas fa-wrench text-blue-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Maintenance</span>
                    </a>
                    
                    <a href="{{ route('tenants.propAssets') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-2">
                            <i class="fas fa-cube text-purple-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Assets</span>
                    </a>
                    
                    <a href="{{ route('tenants.myLeases') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mb-2">
                            <i class="fas fa-file-contract text-orange-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Lease</span>
                    </a>
                </div>
            </div>

            <!-- Lease Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Lease Overview</h3>
                <div class="space-y-4">
                    @if($currentLease)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Monthly Rent</span>
                            <span class="font-semibold text-gray-900">₱{{ number_format($currentLease->rent_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Rent Duration</span>
                            <span class="font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($currentLease->start_date)->format('M j, Y') }} - 
                                {{ \Carbon\Carbon::parse($currentLease->end_date)->format('M j, Y') }}
                            </span>
                        </div>
                        @php
                            $now = \Carbon\Carbon::now();
                            $end = \Carbon\Carbon::parse($currentLease->end_date);
                            $diff = $now->diff($end);
                        @endphp

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Remaining</span>
                            <span class="font-semibold text-gray-900">
                                {{ $diff->m }} month{{ $diff->m != 1 ? 's' : '' }} & {{ $diff->d }} day{{ $diff->d != 1 ? 's' : '' }} left
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Security Deposit</span>
                            <span class="font-semibold text-gray-900">₱{{ number_format($currentLease->deposit_amount, 2) }}</span>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-home text-gray-300 text-3xl mb-2"></i>
                            <p class="text-gray-500 text-sm">No active lease</p>
                            <a href="{{ route('tenants.properties') }}" class="text-blue-600 hover:text-blue-800 text-sm">Browse Properties</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection