@extends('layouts.admin')

@section('title', 'Analytics - SmartRent')
@section('page-title', 'Analytics')
@section('page-description', 'Analyze property performance and tenant insights')

@section('content')
    <!-- Analytics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900">₱{{ number_format($totalRevenue, 2) }}</p>
                </div>                           
            </div>
        </div>

        <!-- Total Properties -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Properties</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalProperties }}</p>
                </div>                           
            </div>
        </div>

        <!-- Occupancy Rate -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-percentage text-orange-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Occupancy Rate</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($occupancyRate, 1) }}%</p>
                </div>                           
            </div>
        </div>

        <!-- Maintenance Requests -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wrench text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Open Maintenance</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $openMaintenanceRequests }}</p>
                </div>                           
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Revenue Overview</h3>
            <div class="flex space-x-2">
                <select id="revenuePeriod" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
        </div>
        <div class="h-80">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Property Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Properties -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Performing Properties</h3>
            <div class="space-y-4">
                @forelse($topProperties as $property)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-building text-blue-600"></i>
                        </div>
                        <div>
                            <!-- FIX: Use array access instead of object property -->
                            <p class="text-sm font-medium text-gray-900">
                                {{ is_array($property) ? ($property['property_name'] ?? 'Unknown Property') : ($property->property_name ?? 'Unknown Property') }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ is_array($property) ? ($property['property_type'] ?? 'N/A') : ($property->property_type ?? 'N/A') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">
                            ₱{{ number_format(is_array($property) ? ($property['revenue'] ?? 0) : ($property->revenue ?? 0), 2) }}
                        </p>
                        <p class="text-xs text-green-600">
                            {{ number_format(is_array($property) ? ($property['occupancy_rate'] ?? 0) : ($property->occupancy_rate ?? 0), 1) }}% occupancy
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <p class="text-gray-500">No property data available</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Maintenance Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintenance Status</h3>
            <div class="space-y-4">
                @foreach($maintenanceStats as $stat)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 
                            @if($stat['status'] == 'pending') bg-orange-100 text-orange-600
                            @elseif($stat['status'] == 'in_progress') bg-blue-100 text-blue-600
                            @elseif($stat['status'] == 'completed') bg-green-100 text-green-600
                            @else bg-red-100 text-red-600 @endif">
                            <i class="fas fa-wrench text-xs"></i>
                        </div>
                        <span class="text-sm text-gray-700 capitalize">
                            {{ str_replace('_', ' ', $stat['status']) }}
                        </span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $stat['count'] }}</span>
                </div>
                @endforeach
            </div>
            
            <!-- Priority Breakdown -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">By Priority</h4>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($priorityStats as $priority)
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-600 mb-1">{{ ucfirst($priority['priority']) }}</p>
                        <p class="text-lg font-bold text-gray-900">{{ $priority['count'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
        <div class="space-y-4">
            @forelse($recentActivities as $activity)
            <div class="flex items-start">
                <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 mt-1
                    @if($activity['type'] == 'payment') bg-green-100 text-green-600
                    @elseif($activity['type'] == 'maintenance') bg-blue-100 text-blue-600
                    @elseif($activity['type'] == 'lease') bg-purple-100 text-purple-600
                    @else bg-gray-100 text-gray-600 @endif">
                    <i class="fas 
                        @if($activity['type'] == 'payment') fa-money-bill-wave
                        @elseif($activity['type'] == 'maintenance') fa-wrench
                        @elseif($activity['type'] == 'lease') fa-file-contract
                        @else fa-bell @endif text-xs"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900">{{ $activity['description'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $activity['time_ago'] }}</p>
                </div>
                @if(isset($activity['amount']))
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-900">₱{{ number_format($activity['amount'], 2) }}</p>
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-4">
                <p class="text-gray-500">No recent activity</p>
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
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            const revenueData = @json($revenueData);
            
            new Chart(revenueCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: revenueData.labels || [],
                    datasets: [{
                        label: 'Revenue',
                        data: revenueData.data || [],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                            callbacks: {
                                label: function(context) {
                                    return '₱' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
        
        // Update chart on period change
        document.getElementById('revenuePeriod').addEventListener('change', function() {
            // In a real implementation, this would fetch new data
            console.log('Period changed to:', this.value);
        });
    });
</script>
@endpush