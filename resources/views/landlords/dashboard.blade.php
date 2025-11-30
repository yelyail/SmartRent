@extends('layouts.landlord')

@section('title', 'Dashboard_landlord - SmartRent')
@section('page-description', 'Welcome back! Here\'s what\'s happening with your properties.')

@section('content')
    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Properties -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Properties</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">24</p>
                    <p class="text-sm text-green-600">+2 from last month</p>
                </div>
            </div>
        </div>

        <!-- Active Tenants -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Active Tenants</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">89</p>
                    <p class="text-sm text-green-600">+5 from last month</p>
                </div>
            </div>
        </div>

        <!-- Smart Devices -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-mobile-alt text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Smart Devices</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">156</p>
                    <p class="text-sm text-green-600">+12 from last month</p>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Monthly Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">$48,320</p>
                    <p class="text-sm text-green-600">+8.2% from last month</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activities -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Activities</h3>
            <div class="space-y-6">
                <!-- Activity Item -->
                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation text-yellow-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">AC Repair Request</h4>
                        <p class="text-sm text-gray-500">Sunset Villa #12</p>
                        <p class="text-xs text-gray-400 mt-1">3 hours ago</p>
                    </div>
                </div>

                <!-- Activity Item -->
                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">New Tenant Check-in</h4>
                        <p class="text-sm text-gray-500">Ocean View #8</p>
                        <p class="text-xs text-gray-400 mt-1">4 hours ago</p>
                    </div>
                </div>

                <!-- Activity Item -->
                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">Smart Lock Battery Low</h4>
                        <p class="text-sm text-gray-500">Garden Court #15</p>
                        <p class="text-xs text-gray-400 mt-1">6 hours ago</p>
                    </div>
                </div>

                <!-- Activity Item -->
                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">Rent Payment Received</h4>
                        <p class="text-sm text-gray-500">Downtown Loft #3</p>
                        <p class="text-xs text-gray-400 mt-1">1 day ago</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Property Overview</h3>
            <div class="space-y-6">
                <!-- Occupied Units -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Occupied Units</span>
                        <span class="text-sm font-semibold text-gray-900">89/96 (93%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 93%"></div>
                    </div>
                </div>

                <!-- Maintenance Requests -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Maintenance Requests</span>
                        <span class="text-sm font-semibold text-gray-900">8 Active</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 35%"></div>
                    </div>
                </div>

                <!-- Smart Device Status -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Smart Device Status</span>
                        <span class="text-sm font-semibold text-gray-900">148/156 Online</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 95%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection