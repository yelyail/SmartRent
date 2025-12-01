@extends('layouts.staff')

@section('title', 'Analytics - SmartRent')
@section('page-title', 'Analytics')
@section('page-description', 'Welcome back! Here\'s what\'s happening with your properties.')

@section('content')
    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Monthly Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 text-lg"></i>
                </div>
                <div class="flex items-center text-green-600 text-sm font-medium">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +8.2%
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Monthly Revenue</p>
                <p class="text-3xl font-bold text-gray-900">$52,000</p>
                <p class="text-xs text-gray-500 mt-1">vs $48,000 last month</p>
            </div>
        </div>

        <!-- Avg Occupancy -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-home text-blue-600 text-lg"></i>
                </div>
                <div class="flex items-center text-green-600 text-sm font-medium">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +2.1%
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Avg Occupancy</p>
                <p class="text-3xl font-bold text-gray-900">93.2%</p>
                <p class="text-xs text-gray-500 mt-1">across all properties</p>
            </div>
        </div>

        <!-- Tenant Turnover -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-lg"></i>
                </div>
                <div class="flex items-center text-red-600 text-sm font-medium">
                    <i class="fas fa-arrow-down text-xs mr-1"></i>
                    -1.2%
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Tenant Turnover</p>
                <p class="text-3xl font-bold text-gray-900">8.5%</p>
                <p class="text-xs text-gray-500 mt-1">annual rate</p>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-orange-600 text-lg"></i>
                </div>
                <div class="flex items-center text-green-600 text-sm font-medium">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +12.3%
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Net Profit</p>
                <p class="text-3xl font-bold text-gray-900">$35,500</p>
                <p class="text-xs text-gray-500 mt-1">this month</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue vs Expenses Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Revenue vs Expenses</h3>
                <div class="flex items-center space-x-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">Revenue</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">Expenses</span>
                    </div>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Property Occupancy -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Property Occupancy</h3>
            <div class="space-y-4">
                <!-- Sunset Villa -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Sunset Villa</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 95%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">95%</span>
                        <i class="fas fa-arrow-up text-green-500 text-xs"></i>
                    </div>
                </div>

                <!-- Downtown Lofts -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Downtown Lofts</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 88%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">88%</span>
                        <i class="fas fa-arrow-down text-red-500 text-xs"></i>
                    </div>
                </div>

                <!-- Garden Court -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Garden Court</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 92%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">92%</span>
                        <i class="fas fa-arrow-up text-green-500 text-xs"></i>
                    </div>
                </div>

                <!-- Tech Hub -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Tech Hub</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 98%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">98%</span>
                        <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                    </div>
                </div>

                <!-- Historic Heights -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Historic Heights</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">85%</span>
                        <i class="fas fa-arrow-down text-red-500 text-xs"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Analytics -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Maintenance Analytics</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Requests This Month -->
            <div class="text-center">
                <div class="text-4xl font-bold text-gray-900 mb-2">15</div>
                <div class="text-sm text-gray-600 mb-1">Requests This Month</div>
                <div class="text-xs text-orange-600">+3 vs last month</div>
            </div>

            <!-- Average Cost -->
            <div class="text-center">
                <div class="text-4xl font-bold text-gray-900 mb-2">$185</div>
                <div class="text-sm text-gray-600 mb-1">Average Cost</div>
                <div class="text-xs text-green-600">-$15 vs last month</div>
            </div>

            <!-- Total This Month -->
            <div class="text-center">
                <div class="text-4xl font-bold text-gray-900 mb-2">$2775</div>
                <div class="text-sm text-gray-600 mb-1">Total This Month</div>
                <div class="text-xs text-orange-600">+$420 vs last month</div>
            </div>

            <!-- Days Avg Resolution -->
            <div class="text-center">
                <div class="text-4xl font-bold text-gray-900 mb-2">2.1</div>
                <div class="text-sm text-gray-600 mb-1">Days Avg Resolution</div>
                <div class="text-xs text-green-600">-0.3 days improvement</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue vs Expenses Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [45000, 48000, 52000, 49000, 55000, 58000],
                    backgroundColor: '#3B82F6',
                    borderRadius: 4,
                    maxBarThickness: 40
                }, {
                    label: 'Expenses',
                    data: [18000, 19000, 17000, 18500, 19500, 20000],
                    backgroundColor: '#EF4444',
                    borderRadius: 4,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
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
                                return '$' + (value / 1000) + 'k';
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
    });
</script>
@endpush