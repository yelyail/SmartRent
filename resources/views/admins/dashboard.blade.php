@extends('layouts.admin')

@section('title', 'Dashboard - SmartRent')
@section('page-title', 'Dashboard')
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
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Properties</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $totalProperties }}</p>
                    <p class="text-sm text-green-600">System Total</p>
                </div>
            </div>
        </div>

        <!-- Active Tenants -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500 mb-1">Active Tenants</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $activeTenants }}</p>
                    <p class="text-sm text-blue-600">{{ $recentTenants->count() }} new this month</p>
                </div>
            </div>
        </div>

        <!-- Active Landlords -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-tie text-white text-lg"></i>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500 mb-1">Active Landlords</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $activeLandlords }}</p>
                    <p class="text-sm text-blue-600">{{ $recentLandlords->count() }} new this month</p>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-white text-lg"></i>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500 mb-1">Monthly Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">₱{{ number_format($monthlyRevenue, 2) }}</p>
                    <p class="text-sm {{ $revenueTrend >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $revenueTrend >= 0 ? '+' : '' }}{{ $revenueTrend }}% from last period
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Overview</h3>
                    <p class="text-sm text-gray-500">Last 6 months</p>
                </div>
                @if($revenueChartData['highest_month'])
                <div class="bg-green-50 px-3 py-1 rounded-lg">
                    <p class="text-sm font-medium text-green-800">
                        <i class="fas fa-trophy mr-1"></i>
                        Highest: {{ $revenueChartData['highest_month'] }}
                    </p>
                </div>
                @endif
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- User Growth Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">User Growth</h3>
                    <p class="text-sm text-gray-500">New registrations by month</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Tenants</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Landlords</span>
                    </div>
                </div>
            </div>
            <div class="h-64">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Activities</h3>
            <div class="space-y-6">
                @forelse($recentActivities as $activity)
                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 bg-{{ $activity['iconColor'] }}-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-{{ $activity['icon'] }} text-{{ $activity['iconColor'] }}-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $activity['title'] }}</h4>
                        <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                        @isset($activity['amount'])
                        <p class="text-sm font-medium text-green-600 mt-1">
                            ₱{{ number_format($activity['amount'], 2) }}
                        </p>
                        @endisset
                        @isset($activity['priority'])
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mt-1
                            @if($activity['priority'] == 'high' || $activity['priority'] == 'urgent') bg-red-100 text-red-800
                            @elseif($activity['priority'] == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ $activity['priority'] }}
                        </span>
                        @endisset
                        @isset($activity['role'])
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mt-1
                            @if($activity['role'] == 'landlord') bg-purple-100 text-purple-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ $activity['role'] }}
                        </span>
                        @endisset
                        <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-history text-gray-300 text-3xl mb-2"></i>
                    <p class="text-gray-500">No recent activities</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Registrations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Registrations</h3>
                <div class="flex space-x-2">
                    <span class="text-sm text-blue-600 font-medium">{{ $recentTenants->count() }} Tenants</span>
                    <span class="text-sm text-purple-600 font-medium">{{ $recentLandlords->count() }} Landlords</span>
                </div>
            </div>
            
            <!-- Recent Tenants -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">New Tenants</h4>
                <div class="space-y-3">
                    @forelse($recentTenants as $tenant)
                    <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $tenant->first_name }} {{ $tenant->last_name }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $tenant->email }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400">{{ $tenant->created_at->diffForHumans() }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-2">No new tenants</p>
                    @endforelse
                </div>
            </div>
            
            <!-- Recent Landlords -->
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-3">New Landlords</h4>
                <div class="space-y-3">
                    @forelse($recentLandlords as $landlord)
                    <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-tie text-purple-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $landlord->first_name }} {{ $landlord->last_name }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $landlord->email }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400">{{ $landlord->created_at->diffForHumans() }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-2">No new landlords</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- System Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">System Overview</h3>
            <div class="space-y-6">
                <!-- Occupied Units -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Occupied Units</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $occupiedUnits }}/{{ $totalUnits }} ({{ $occupancyRate }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $occupancyRate }}%"></div>
                    </div>
                </div>

                <!-- Maintenance Requests -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Active Maintenance</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $activeMaintenance }} Requests</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ min($activeMaintenance * 10, 100) }}%"></div>
                    </div>
                </div>

                <!-- Smart Device Status -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Smart Devices</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $onlineDevices }}/{{ $smartDevices }} Online</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $deviceOnlineRate }}%"></div>
                    </div>
                </div>

                <!-- Top Properties -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Top Properties</h4>
                    <div class="space-y-3">
                        @forelse($topProperties as $property)
                        <div class="flex items-center justify-between p-2">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $property->property_name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $property->active_leases_count ?? 0 }}/{{ $property->units_count ?? 0 }} units occupied
                                </p>
                            </div>
                            <span class="text-sm font-semibold 
                                @if(($property->occupancy_rate ?? 0) >= 80) text-green-600
                                @elseif(($property->occupancy_rate ?? 0) >= 50) text-yellow-600
                                @else text-red-600 @endif">
                                {{ $property->occupancy_rate ?? 0 }}%
                            </span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 text-center py-2">No properties found</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart (Bar)
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: @json($revenueChartData['labels']),
                datasets: [{
                    label: 'Revenue',
                    data: @json($revenueChartData['data']),
                    backgroundColor: '#3B82F6',
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toLocaleString('en-PH', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#F3F4F6'
                        },
                        ticks: {
                            color: '#6B7280',
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return '₱' + (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return '₱' + (value / 1000).toFixed(0) + 'K';
                                } else {
                                    return '₱' + value;
                                }
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6B7280'
                        }
                    }
                }
            }
        });

        // User Growth Chart (Line)
        const userCtx = document.getElementById('userGrowthChart').getContext('2d');
        new Chart(userCtx, {
            type: 'line',
            data: {
                labels: @json($userGrowthData['labels']),
                datasets: [{
                    label: 'Tenants',
                    data: @json($userGrowthData['tenants']),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Landlords',
                    data: @json($userGrowthData['landlords']),
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#F3F4F6'
                        },
                        ticks: {
                            color: '#6B7280',
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6B7280'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    });
</script>
@endpush