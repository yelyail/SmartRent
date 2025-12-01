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
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Properties</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $totalProperties }}</p>
                    <p class="text-sm text-green-600">+0 from last month</p>
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
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $totalTenants }}</p>
                    <p class="text-sm text-green-600">+0 from last month</p>
                </div>
            </div>
        </div>

        <!-- Smart Devices -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-mobile-alt text-white text-lg"></i>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500 mb-1">Smart Devices</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $smartDevices }}</p>
                    <p class="text-sm text-green-600">+0 from last month</p>
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

    <!-- Charts and Stats Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Monthly Revenue Trend</h3>
                <span class="text-sm text-gray-500">Last 6 months</span>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Property Stats -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Property Overview</h3>
            <div class="space-y-6">
                <!-- Total Units -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Total Units</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $totalUnits }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <p class="font-medium text-blue-700">Occupied</p>
                            <p class="text-xl font-bold text-gray-900">{{ $occupiedUnits }}</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <p class="font-medium text-green-700">Available</p>
                            <p class="text-xl font-bold text-gray-900">{{ $availableUnits }}</p>
                        </div>
                    </div>
                </div>

                <!-- Occupancy Rate -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Occupancy Rate</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $occupancyRate }}%</span>
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
                        <span class="text-sm font-medium text-gray-700">Smart Devices Online</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $onlineDevices }}/{{ $smartDevices }} ({{ $deviceOnlineRate }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $deviceOnlineRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activities</h3>
            <a href="{{ route('landlords.analytics') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                View All
            </a>
        </div>
        
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
                        <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-history text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Recent Activities</h3>
                    <p class="text-gray-500">No recent activities found for your properties.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($revenueChartData['labels']),
                datasets: [{
                    label: 'Monthly Revenue',
                    data: @json($revenueChartData['data']),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
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

        // Find month with highest revenue
        const revenues = @json($revenueChartData['data']);
        const maxRevenue = Math.max(...revenues);
        const maxMonthIndex = revenues.indexOf(maxRevenue);
        const maxMonth = @json($revenueChartData['labels'])[maxMonthIndex];
        
        // Add annotation for highest revenue month
        if (maxRevenue > 0) {
            revenueChart.options.plugins.annotation = {
                annotations: {
                    line1: {
                        type: 'line',
                        xMin: maxMonthIndex,
                        xMax: maxMonthIndex,
                        borderColor: '#EF4444',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        label: {
                            content: `Highest: ₱${maxRevenue.toLocaleString()}`,
                            enabled: true,
                            position: 'top',
                            backgroundColor: '#EF4444',
                            color: 'white',
                            font: {
                                size: 12
                            },
                            padding: 6
                        }
                    }
                }
            };
            revenueChart.update();
        }
    });
</script>
@endpush