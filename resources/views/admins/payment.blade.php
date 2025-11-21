@extends('layouts.admin')

@section('title', 'Payments & Invoices - SmartRent')
@section('page-title', 'Payments & Invoices')
@section('page-description', 'Manage payments, invoices, and billing records')

@section('header-actions')
    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Create Invoice
    </button>
    <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors flex items-center">
        <i class="fas fa-file-export mr-2"></i>
        Export
    </button>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Collected -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 text-lg"></i>
                </div>
                <div class="flex items-center text-green-600 text-sm font-medium">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +12.5%
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Collected</p>
                <p class="text-3xl font-bold text-gray-900">$52,480</p>
                <p class="text-xs text-gray-500 mt-1">This month</p>
            </div>
        </div>

        <!-- Outstanding Balance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                </div>
                <div class="flex items-center text-red-600 text-sm font-medium">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +$2,850
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Outstanding Balance</p>
                <p class="text-3xl font-bold text-gray-900">$8,750</p>
                <p class="text-xs text-gray-500 mt-1">Across all properties</p>
            </div>
        </div>

        <!-- Invoices Sent -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-receipt text-blue-600 text-lg"></i>
                </div>
                <div class="flex items-center text-green-600 text-sm font-medium">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +8
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Invoices Sent</p>
                <p class="text-3xl font-bold text-gray-900">42</p>
                <p class="text-xs text-gray-500 mt-1">This month</p>
            </div>
        </div>

        <!-- Overdue Invoices -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <div class="flex items-center text-red-600 text-sm font-medium">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                    +3
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Overdue Invoices</p>
                <p class="text-3xl font-bold text-gray-900">7</p>
                <p class="text-xs text-gray-500 mt-1">Require attention</p>
            </div>
        </div>
    </div>

    <!-- Payment Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Payment Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Payment Status Overview</h3>
            <div class="space-y-4">
                <!-- Paid -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Paid</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 78%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">78%</span>
                        <span class="text-xs text-gray-500">35 invoices</span>
                    </div>
                </div>

                <!-- Pending -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Pending</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: 16%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">16%</span>
                        <span class="text-xs text-gray-500">7 invoices</span>
                    </div>
                </div>

                <!-- Overdue -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Overdue</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: 6%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">6%</span>
                        <span class="text-xs text-gray-500">3 invoices</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-4">
                <button class="bg-blue-50 text-blue-700 p-4 rounded-lg hover:bg-blue-100 transition-colors flex flex-col items-center justify-center">
                    <i class="fas fa-plus text-lg mb-2"></i>
                    <span class="text-sm font-medium">New Invoice</span>
                </button>
                <button class="bg-green-50 text-green-700 p-4 rounded-lg hover:bg-green-100 transition-colors flex flex-col items-center justify-center">
                    <i class="fas fa-file-import text-lg mb-2"></i>
                    <span class="text-sm font-medium">Record Payment</span>
                </button>
                <button class="bg-purple-50 text-purple-700 p-4 rounded-lg hover:bg-purple-100 transition-colors flex flex-col items-center justify-center">
                    <i class="fas fa-envelope text-lg mb-2"></i>
                    <span class="text-sm font-medium">Send Reminders</span>
                </button>
                <button class="bg-orange-50 text-orange-700 p-4 rounded-lg hover:bg-orange-100 transition-colors flex flex-col items-center justify-center">
                    <i class="fas fa-chart-bar text-lg mb-2"></i>
                    <span class="text-sm font-medium">Reports</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Recent Payments</h3>
            <button class="text-blue-600 text-sm font-medium hover:text-blue-800 transition-colors">
                View All
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500 border-b border-gray-200">
                        <th class="pb-3 font-medium">Payment ID</th>
                        <th class="pb-3 font-medium">Tenant</th>
                        <th class="pb-3 font-medium">Property</th>
                        <th class="pb-3 font-medium">Amount</th>
                        <th class="pb-3 font-medium">Date</th>
                        <th class="pb-3 font-medium">Method</th>
                        <th class="pb-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="py-4 text-sm font-medium text-gray-900">#INV-2023-045</td>
                        <td class="py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Michael Chen</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-900">Sunset Villa, Apt 3B</td>
                        <td class="py-4 text-sm font-medium text-gray-900">$1,850</td>
                        <td class="py-4 text-sm text-gray-500">Jun 5, 2023</td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Bank Transfer
                            </span>
                        </td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Completed
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4 text-sm font-medium text-gray-900">#INV-2023-044</td>
                        <td class="py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Sarah Johnson</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-900">Downtown Lofts, Unit 7A</td>
                        <td class="py-4 text-sm font-medium text-gray-900">$2,200</td>
                        <td class="py-4 text-sm text-gray-500">Jun 3, 2023</td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Credit Card
                            </span>
                        </td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Completed
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4 text-sm font-medium text-gray-900">#INV-2023-043</td>
                        <td class="py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Robert Williams</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-900">Garden Court, Unit 12</td>
                        <td class="py-4 text-sm font-medium text-gray-900">$1,650</td>
                        <td class="py-4 text-sm text-gray-500">Jun 2, 2023</td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                PayPal
                            </span>
                        </td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Processing
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4 text-sm font-medium text-gray-900">#INV-2023-042</td>
                        <td class="py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Jennifer Martinez</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-900">Tech Hub, Apt 5C</td>
                        <td class="py-4 text-sm font-medium text-gray-900">$2,450</td>
                        <td class="py-4 text-sm text-gray-500">May 30, 2023</td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Bank Transfer
                            </span>
                        </td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Completed
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Outstanding Invoices -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Outstanding Invoices</h3>
            <button class="text-blue-600 text-sm font-medium hover:text-blue-800 transition-colors">
                Send Reminders
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500 border-b border-gray-200">
                        <th class="pb-3 font-medium">Invoice #</th>
                        <th class="pb-3 font-medium">Tenant</th>
                        <th class="pb-3 font-medium">Due Date</th>
                        <th class="pb-3 font-medium">Amount</th>
                        <th class="pb-3 font-medium">Days Overdue</th>
                        <th class="pb-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="py-4 text-sm font-medium text-gray-900">#INV-2023-038</td>
                        <td class="py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">David Thompson</p>
                                    <p class="text-xs text-gray-500">Historic Heights, Unit 8</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-500">May 25, 2023</td>
                        <td class="py-4 text-sm font-medium text-gray-900">$1,950</td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                12 days
                            </span>
                        </td>
                        <td class="py-4">
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Remind
                                </button>
                                <button class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Escalate
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4 text-sm font-medium text-gray-900">#INV-2023-039</td>
                        <td class="py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Amanda Wilson</p>
                                    <p class="text-xs text-gray-500">Riverside Apartments, Unit 4</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-500">May 28, 2023</td>
                        <td class="py-4 text-sm font-medium text-gray-900">$1,750</td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                9 days
                            </span>
                        </td>
                        <td class="py-4">
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Remind
                                </button>
                                <button class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Escalate
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4 text-sm font-medium text-gray-900">#INV-2023-041</td>
                        <td class="py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">James Anderson</p>
                                    <p class="text-xs text-gray-500">Mountain View, Unit 15</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-500">Jun 1, 2023</td>
                        <td class="py-4 text-sm font-medium text-gray-900">$2,100</td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                5 days
                            </span>
                        </td>
                        <td class="py-4">
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Remind
                                </button>
                                <button class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Escalate
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection