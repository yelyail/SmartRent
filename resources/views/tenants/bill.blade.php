@extends('layouts.tenants')

@section('title', 'Lease & Billing_ten - SmartRent')
@section('page-title', 'Lease & Billing')
@section('page-description', 'Welcome back! Here\'s what\'s happening with your properties.')

@section('content')
    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Collected -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 text-lg"></i>
                </div>
                <div class="flex items-center text-green-600 text-sm font-medium">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +5.8%
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Collected</p>
                <p class="text-3xl font-bold text-gray-900">$48,500</p>
                <p class="text-xs text-gray-500 mt-1">vs $45,800 last month</p>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                </div>
                <div class="flex items-center text-red-600 text-sm font-medium">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +2
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Pending Payments</p>
                <p class="text-3xl font-bold text-gray-900">7</p>
                <p class="text-xs text-gray-500 mt-1">overdue this month</p>
            </div>
        </div>

        <!-- Active Leases -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-contract text-blue-600 text-lg"></i>
                </div>
                <div class="flex items-center text-green-600 text-sm font-medium">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +3
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Active Leases</p>
                <p class="text-3xl font-bold text-gray-900">42</p>
                <p class="text-xs text-gray-500 mt-1">across all properties</p>
            </div>
        </div>

        <!-- Upcoming Renewals -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-purple-600 text-lg"></i>
                </div>
                <div class="flex items-center text-orange-600 text-sm font-medium">
                    <i class="fas fa-exclamation text-xs mr-1"></i>
                    5
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Upcoming Renewals</p>
                <p class="text-3xl font-bold text-gray-900">5</p>
                <p class="text-xs text-gray-500 mt-1">within 30 days</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Payment Status Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Payment Status</h3>
                <div class="flex items-center space-x-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">Paid</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">Pending</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">Overdue</span>
                    </div>
                </div>
            </div>
            <div class="h-64">
                <canvas id="paymentChart"></canvas>
            </div>
        </div>

        <!-- Lease Expiration Timeline -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Lease Expiration Timeline</h3>
            <div class="space-y-4">
                <!-- Michael Chen -->
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Michael Chen</span>
                        <p class="text-xs text-gray-500">Sunset Villa, Apt 3B</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-900">Jun 15, 2023</span>
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    </div>
                </div>

                <!-- Sarah Johnson -->
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Sarah Johnson</span>
                        <p class="text-xs text-gray-500">Downtown Lofts, Unit 7A</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-900">Jun 30, 2023</span>
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    </div>
                </div>

                <!-- Robert Williams -->
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Robert Williams</span>
                        <p class="text-xs text-gray-500">Garden Court, Unit 12</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-900">Jul 5, 2023</span>
                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                    </div>
                </div>

                <!-- Jennifer Martinez -->
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Jennifer Martinez</span>
                        <p class="text-xs text-gray-500">Tech Hub, Apt 5C</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-900">Jul 22, 2023</span>
                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                    </div>
                </div>

                <!-- David Thompson -->
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm font-medium text-gray-700">David Thompson</span>
                        <p class="text-xs text-gray-500">Historic Heights, Unit 8</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-900">Aug 10, 2023</span>
                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
            <button class="text-blue-600 text-sm font-medium hover:text-blue-800 transition-colors">
                View All
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500 border-b border-gray-200">
                        <th class="pb-3 font-medium">Tenant</th>
                        <th class="pb-3 font-medium">Property</th>
                        <th class="pb-3 font-medium">Amount</th>
                        <th class="pb-3 font-medium">Due Date</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Michael Chen</p>
                                    <p class="text-xs text-gray-500">michael@email.com</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-900">Sunset Villa, Apt 3B</td>
                        <td class="py-4 text-sm font-medium text-gray-900">$1,850</td>
                        <td class="py-4 text-sm text-gray-500">Jun 1, 2023</td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Paid
                            </span>
                        </td>
                        <td class="py-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Sarah Johnson</p>
                                    <p class="text-xs text-gray-500">sarah@email.com</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-900">Downtown Lofts, Unit 7A</td>
                        <td class="py-4 text-sm font-medium text-gray-900">$2,200</td>
                        <td class="py-4 text-sm text-gray-500">Jun 1, 2023</td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        </td>
                        <td class="py-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Send Reminder
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Robert Williams</p>
                                    <p class="text-xs text-gray-500">robert@email.com</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-900">Garden Court, Unit 12</td>
                        <td class="py-4 text-sm font-medium text-gray-900">$1,650</td>
                        <td class="py-4 text-sm text-gray-500">May 28, 2023</td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Overdue
                            </span>
                        </td>
                        <td class="py-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Send Notice
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Jennifer Martinez</p>
                                    <p class="text-xs text-gray-500">jennifer@email.com</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-900">Tech Hub, Apt 5C</td>
                        <td class="py-4 text-sm font-medium text-gray-900">$2,450</td>
                        <td class="py-4 text-sm text-gray-500">Jun 1, 2023</td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Paid
                            </span>
                        </td>
                        <td class="py-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Details
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Payment Status Chart
        const ctx = document.getElementById('paymentChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Pending', 'Overdue'],
                datasets: [{
                    data: [35, 7, 3],
                    backgroundColor: [
                        '#10B981',
                        '#F59E0B',
                        '#EF4444'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush