@extends('layouts.admin')

@section('title', 'Reports - SmartRent')
@section('page-title', 'Reports')
@section('page-description', 'Generate and export detailed property reports')

@section('content')
    <!-- Report Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-lg font-semibold text-gray-900">Report Filters</h3>
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="printReport()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
                <button onclick="exportToPDF()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <!-- Report Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                <select id="reportType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="financial">Financial Summary</option>
                    <option value="occupancy">Occupancy Report</option>
                    <option value="maintenance">Maintenance Report</option>
                    <option value="tenant">Tenant Report</option>
                    <option value="expense">Expense Report</option>
                </select>
            </div>
            
            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <select id="dateRange" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="this_month">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="this_quarter">This Quarter</option>
                    <option value="this_year">This Year</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
            
            <!-- Property Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Property</label>
                <select id="propertySelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">All Properties</option>
                    <?php
                        use Illuminate\Support\Str;
                    ?>
                    @foreach($properties as $property)
                    <?php
                        $propertyName = isset($property['name']) ? $property['name'] : 'Unknown Property';
                        $propertySlug = Str::slug($propertyName);
                    ?>
                    <option value="{{ $propertySlug }}">{{ $propertyName }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Generate Button -->
            <div class="flex items-end">
                <button onclick="generateReport()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-chart-bar mr-2"></i> Generate Report
                </button>
            </div>
        </div>
        
        <!-- Custom Date Range (hidden by default) -->
        <div id="customDateRange" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Financial Summary Report -->
    <div id="financialReport" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <?php
            // Extract financial report data with safe defaults
            $periodDisplay = isset($financialReport['period']['display']) 
                ? $financialReport['period']['display'] 
                : 'Current Period';
            
            $totalRevenue = isset($financialReport['summary']['total_revenue']) 
                ? $financialReport['summary']['total_revenue'] 
                : 0;
            $revenueChange = isset($financialReport['summary']['revenue_change']) 
                ? $financialReport['summary']['revenue_change'] 
                : 0;
            $totalExpenses = isset($financialReport['summary']['total_expenses']) 
                ? $financialReport['summary']['total_expenses'] 
                : 0;
            $expensesChange = isset($financialReport['summary']['expenses_change']) 
                ? $financialReport['summary']['expenses_change'] 
                : 0;
            $netProfit = isset($financialReport['summary']['net_profit']) 
                ? $financialReport['summary']['net_profit'] 
                : 0;
            $profitChange = isset($financialReport['summary']['profit_change']) 
                ? $financialReport['summary']['profit_change'] 
                : 0;
            $profitMargin = isset($financialReport['summary']['profit_margin']) 
                ? $financialReport['summary']['profit_margin'] 
                : 0;
        ?>
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Financial Summary Report</h3>
                <p class="text-gray-600">{{ $periodDisplay }} | All Properties</p>
            </div>
            <div class="text-sm text-gray-500">
                Generated: {{ date('M j, Y H:i') }}
            </div>
        </div>
        
        <!-- Financial Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalRevenue, 2) }}</p>
                <?php
                    $revenueChangeClass = $revenueChange >= 0 ? 'text-green-600' : 'text-red-600';
                    $revenueChangeSign = $revenueChange >= 0 ? '+' : '';
                ?>
                <p class="text-xs {{ $revenueChangeClass }} mt-1">
                    {{ $revenueChangeSign }}{{ $revenueChange }}% from last month
                </p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Total Expenses</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalExpenses, 2) }}</p>
                <?php
                    $expensesChangeClass = $expensesChange >= 0 ? 'text-orange-600' : 'text-green-600';
                    $expensesChangeSign = $expensesChange >= 0 ? '+' : '';
                ?>
                <p class="text-xs {{ $expensesChangeClass }} mt-1">
                    {{ $expensesChangeSign }}{{ $expensesChange }}% from last month
                </p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Net Profit</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($netProfit, 2) }}</p>
                <?php
                    $profitChangeClass = $profitChange >= 0 ? 'text-green-600' : 'text-red-600';
                    $profitChangeSign = $profitChange >= 0 ? '+' : '';
                ?>
                <p class="text-xs {{ $profitChangeClass }} mt-1">
                    {{ $profitChangeSign }}{{ $profitChange }}% from last month
                </p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Profit Margin</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($profitMargin, 1) }}%</p>
                <?php
                    $marginChange = $profitMargin - 66.5;
                ?>
                <p class="text-xs text-green-600 mt-1">
                    +{{ number_format($marginChange, 1) }}% from last month
                </p>
            </div>
        </div>
        
        <!-- Detailed Income Table -->
        <?php
            $propertyIncome = isset($financialReport['property_income']) 
                ? $financialReport['property_income'] 
                : [];
        ?>
        
        @if(!empty($propertyIncome))
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Rental Income by Property</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Occupied</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rental Income</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Rent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                            $totalUnits = 0;
                            $totalOccupied = 0;
                            $totalIncome = 0;
                        ?>
                        
                        @foreach($propertyIncome as $property)
                        <?php
                            $propertyUnits = isset($property['units']) ? $property['units'] : 0;
                            $propertyOccupied = isset($property['occupied']) ? $property['occupied'] : 0;
                            $propertyIncomeAmount = isset($property['rental_income']) ? $property['rental_income'] : 0;
                            
                            $totalUnits += $propertyUnits;
                            $totalOccupied += $propertyOccupied;
                            $totalIncome += $propertyIncomeAmount;
                            
                            $statusColors = [
                                'excellent' => 'bg-green-100 text-green-800',
                                'good' => 'bg-yellow-100 text-yellow-800',
                                'needs_attention' => 'bg-red-100 text-red-800'
                            ];
                            $propertyStatus = isset($property['status']) ? $property['status'] : 'needs_attention';
                            $propertyName = isset($property['name']) ? $property['name'] : 'Unknown Property';
                            $avgRent = isset($property['avg_rent']) ? $property['avg_rent'] : 0;
                            $occupancyRate = isset($property['occupancy_rate']) ? $property['occupancy_rate'] : 0;
                        ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $propertyName }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $propertyUnits }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $propertyOccupied }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">${{ number_format($propertyIncomeAmount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($avgRent, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ isset($statusColors[$propertyStatus]) ? $statusColors[$propertyStatus] : 'bg-gray-100 text-gray-800' }}">
                                    {{ number_format($occupancyRate, 1) }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $totalUnits }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $totalOccupied }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${{ number_format($totalIncome, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                <?php
                                    $avgTotalRent = $totalOccupied > 0 ? $totalIncome / $totalOccupied : 0;
                                ?>
                                ${{ number_format($avgTotalRent, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                <?php
                                    $totalOccupancyRate = $totalUnits > 0 ? ($totalOccupied / $totalUnits) * 100 : 0;
                                ?>
                                {{ number_format($totalOccupancyRate, 1) }}%
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif
        
        <!-- Expense Breakdown -->
        <?php
            $expenseBreakdown = isset($financialReport['expense_breakdown']) 
                ? $financialReport['expense_breakdown'] 
                : [];
            $chartData = isset($financialReport['chart_data']) 
                ? $financialReport['chart_data'] 
                : ['labels' => [], 'data' => [], 'colors' => []];
        ?>
        
        @if(!empty($expenseBreakdown))
        <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Expense Breakdown</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="space-y-3">
                        <?php
                            $expensesTotal = 0;
                        ?>
                        @foreach($expenseBreakdown as $expense)
                        <?php
                            $expenseAmount = isset($expense['amount']) ? $expense['amount'] : 0;
                            $expensesTotal += $expenseAmount;
                            $expenseCategory = isset($expense['category']) ? $expense['category'] : 'Unknown Category';
                        ?>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ $expenseCategory }}</span>
                            <span class="font-semibold">₱{{ number_format($expenseAmount, 2) }}</span>
                        </div>
                        @endforeach
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-900">Total Expenses</span>
                                <span class="text-lg font-bold text-gray-900">₱{{ number_format($expensesTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty($chartData['data']))
                <div class="h-64">
                    <canvas id="expenseChart"></canvas>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Occupancy Report -->
    <div id="occupancyReport" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <?php
            // Extract occupancy report data with safe defaults
            $occupancyPeriod = isset($occupancyReport['period']) 
                ? $occupancyReport['period'] 
                : 'Current Period';
            $overallOccupancy = isset($occupancyReport['overall_occupancy']) 
                ? $occupancyReport['overall_occupancy'] 
                : 0;
            $occupiedUnits = isset($occupancyReport['occupied_units']) 
                ? $occupancyReport['occupied_units'] 
                : 0;
            $totalUnits = isset($occupancyReport['total_units']) 
                ? $occupancyReport['total_units'] 
                : 0;
            $vacantUnits = isset($occupancyReport['vacant_units']) 
                ? $occupancyReport['vacant_units'] 
                : 0;
            $occupancyProperties = isset($occupancyReport['properties']) 
                ? $occupancyReport['properties'] 
                : [];
        ?>
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Occupancy Report</h3>
                <p class="text-gray-600">{{ $occupancyPeriod }} | All Properties</p>
            </div>
            <div class="text-sm text-gray-500">
                Generated: {{ date('M j, Y H:i') }}
            </div>
        </div>
        
        <!-- Occupancy Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-3xl font-bold text-blue-900">{{ number_format($overallOccupancy, 1) }}%</div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-home text-blue-600 text-lg"></i>
                    </div>
                </div>
                <p class="text-sm text-blue-700 mb-1">Overall Occupancy Rate</p>
                <p class="text-xs text-blue-600">
                    {{ $occupiedUnits }} of {{ $totalUnits }} units occupied
                </p>
            </div>
            
            <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-3xl font-bold text-green-900">{{ $occupiedUnits }}</div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600 text-lg"></i>
                    </div>
                </div>
                <p class="text-sm text-green-700 mb-1">Occupied Units</p>
                <p class="text-xs text-green-600">Currently renting</p>
            </div>
            
            <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-3xl font-bold text-red-900">{{ $vacantUnits }}</div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-times text-red-600 text-lg"></i>
                    </div>
                </div>
                <p class="text-sm text-red-700 mb-1">Vacant Units</p>
                <p class="text-xs text-red-600">Available for rent</p>
            </div>
        </div>
        
        <!-- Property-wise Occupancy -->
        @if(!empty($occupancyProperties))
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Property-wise Occupancy</h4>
            <div class="space-y-4">
                @foreach($occupancyProperties as $property)
                <?php
                    $propertyName = isset($property['name']) ? $property['name'] : 'Unknown Property';
                    $occupancyRate = isset($property['occupancy_rate']) ? $property['occupancy_rate'] : 0;
                    $occupiedUnitsProp = isset($property['occupied_units']) ? $property['occupied_units'] : 0;
                    $totalUnitsProp = isset($property['total_units']) ? $property['total_units'] : 0;
                    $vacantUnitsProp = $totalUnitsProp - $occupiedUnitsProp;
                    
                    // Determine colors based on occupancy rate
                    $rateColorClass = $occupancyRate >= 90 ? 'text-green-600' : ($occupancyRate >= 80 ? 'text-yellow-600' : 'text-red-600');
                    $rateBarClass = $occupancyRate >= 90 ? 'bg-green-500' : ($occupancyRate >= 80 ? 'bg-yellow-500' : 'bg-red-500');
                ?>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-gray-900">{{ $propertyName }}</span>
                            <span class="font-semibold {{ $rateColorClass }}">
                                {{ number_format($occupancyRate, 1) }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full {{ $rateBarClass }}"
                                 style="width: {{ min($occupancyRate, 100) }}%"></div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-xs text-gray-500">{{ $occupiedUnitsProp }} occupied</span>
                            <span class="text-xs text-gray-500">{{ $vacantUnitsProp }} vacant</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Maintenance Report -->
    <div id="maintenanceReport" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <?php
            // Extract maintenance report data with safe defaults
            $maintenancePeriod = isset($maintenanceReport['period']) 
                ? $maintenanceReport['period'] 
                : 'Current Period';
            $totalRequests = isset($maintenanceReport['total_requests']) 
                ? $maintenanceReport['total_requests'] 
                : 0;
            $openRequests = isset($maintenanceReport['open_requests']) 
                ? $maintenanceReport['open_requests'] 
                : 0;
            $completedRequests = isset($maintenanceReport['completed_requests']) 
                ? $maintenanceReport['completed_requests'] 
                : 0;
            $avgResolutionTime = isset($maintenanceReport['avg_resolution_time']) 
                ? $maintenanceReport['avg_resolution_time'] 
                : '0 days';
            $maintenanceCost = isset($maintenanceReport['total_cost']) 
                ? $maintenanceReport['total_cost'] 
                : 0;
            $priorityRequests = isset($maintenanceReport['requests_by_priority']) 
                ? $maintenanceReport['requests_by_priority'] 
                : [];
            $recentRequests = isset($maintenanceReport['recent_requests']) 
                ? $maintenanceReport['recent_requests'] 
                : [];
        ?>
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Maintenance Report</h3>
                <p class="text-gray-600">{{ $maintenancePeriod }} | All Properties</p>
            </div>
            <div class="text-sm text-gray-500">
                Generated: {{ date('M j, Y H:i') }}
            </div>
        </div>
        
        <!-- Maintenance Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Total Requests</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalRequests }}</p>
                <p class="text-xs text-blue-600 mt-1">This month</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Open Requests</p>
                <p class="text-2xl font-bold text-gray-900">{{ $openRequests }}</p>
                <p class="text-xs text-orange-600 mt-1">Pending resolution</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Completed</p>
                <p class="text-2xl font-bold text-gray-900">{{ $completedRequests }}</p>
                <p class="text-xs text-green-600 mt-1">Resolved this month</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Avg Resolution Time</p>
                <p class="text-2xl font-bold text-gray-900">{{ $avgResolutionTime }}</p>
                <p class="text-xs text-purple-600 mt-1">Days to complete</p>
            </div>
        </div>
        
        <!-- Requests by Priority -->
        @if(!empty($priorityRequests))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Requests by Priority</h4>
                <div class="space-y-3">
                    @foreach($priorityRequests as $priority)
                    <?php
                        $priorityColor = isset($priority['color']) ? $priority['color'] : 'bg-gray-100 text-gray-800';
                        $priorityName = isset($priority['priority']) ? $priority['priority'] : 'Unknown';
                        $priorityCount = isset($priority['count']) ? $priority['count'] : 0;
                        $percentage = $totalRequests > 0 ? ($priorityCount / $totalRequests) * 100 : 0;
                    ?>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $priorityColor }}">
                                {{ $priorityName }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">{{ $priorityCount }} requests</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ number_format($percentage, 1) }}%
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Total Maintenance Cost</h4>
                <?php
                    $avgCostPerRequest = $completedRequests > 0 ? $maintenanceCost / $completedRequests : 0;
                ?>
                <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                    <div class="text-4xl font-bold text-yellow-900 mb-2">${{ number_format($maintenanceCost, 2) }}</div>
                    <p class="text-sm text-yellow-700 mb-1">Spent on maintenance this month</p>
                    <p class="text-xs text-yellow-600">
                        Average cost per request: ${{ number_format($avgCostPerRequest, 2) }}
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Recent Maintenance Requests -->
        @if(!empty($recentRequests))
        <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Recent Maintenance Requests</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property/Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentRequests as $request)
                        <?php
                            $priorityColors = [
                                'urgent' => 'bg-red-100 text-red-800',
                                'high' => 'bg-orange-100 text-orange-800',
                                'medium' => 'bg-yellow-100 text-yellow-800',
                                'low' => 'bg-green-100 text-green-800'
                            ];
                            $requestPriority = isset($request['priority']) ? $request['priority'] : 'medium';
                            $requestStatus = isset($request['status']) ? $request['status'] : 'Pending';
                            $priorityColor = isset($priorityColors[$requestPriority]) ? $priorityColors[$requestPriority] : 'bg-gray-100 text-gray-800';
                            $statusColor = $requestStatus === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                            
                            $requestId = isset($request['id']) ? $request['id'] : 'N/A';
                            $requestProperty = isset($request['property']) ? $request['property'] : 'N/A';
                            $requestUnit = isset($request['unit']) ? $request['unit'] : '';
                            $requestType = isset($request['type']) ? $request['type'] : 'Unknown';
                            $requestCost = isset($request['cost']) ? $request['cost'] : 0;
                        ?>
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $requestId }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $requestProperty }}</div>
                                <div class="text-xs text-gray-400">{{ $requestUnit }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($requestType) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $priorityColor }}">
                                    {{ ucfirst($requestPriority) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                    {{ ucfirst($requestStatus) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">${{ number_format($requestCost, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Tenant Report -->
    <div id="tenantReport" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <?php
            // Extract tenant report data with safe defaults
            $tenantPeriod = isset($tenantReport['period']) 
                ? $tenantReport['period'] 
                : 'Current Period';
            $totalTenants = isset($tenantReport['total_tenants']) 
                ? $tenantReport['total_tenants'] 
                : 0;
            $newTenants = isset($tenantReport['new_tenants']) 
                ? $tenantReport['new_tenants'] 
                : 0;
            $departedTenants = isset($tenantReport['departed_tenants']) 
                ? $tenantReport['departed_tenants'] 
                : 0;
            $paymentStatusData = isset($tenantReport['payment_status']) 
                ? $tenantReport['payment_status'] 
                : [];
            $onTimePayments = isset($paymentStatusData['on_time']) 
                ? $paymentStatusData['on_time'] 
                : 0;
            $latePayments = isset($paymentStatusData['late']) 
                ? $paymentStatusData['late'] 
                : 0;
            $overduePayments = isset($paymentStatusData['overdue']) 
                ? $paymentStatusData['overdue'] 
                : 0;
            $percentageOnTime = isset($paymentStatusData['percentage_on_time']) 
                ? $paymentStatusData['percentage_on_time'] 
                : 0;
        ?>
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Tenant Report</h3>
                <p class="text-gray-600">{{ $tenantPeriod }} | All Properties</p>
            </div>
            <div class="text-sm text-gray-500">
                Generated: {{ date('M j, Y H:i') }}
            </div>
        </div>
        
        <!-- Tenant Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Total Tenants</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalTenants }}</p>
                <p class="text-xs text-blue-600 mt-1">Currently renting</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">New Tenants</p>
                <p class="text-2xl font-bold text-gray-900">{{ $newTenants }}</p>
                <p class="text-xs text-green-600 mt-1">This month</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Departed</p>
                <p class="text-2xl font-bold text-gray-900">{{ $departedTenants }}</p>
                <p class="text-xs text-red-600 mt-1">This month</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Payment On-time Rate</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($percentageOnTime, 1) }}%</p>
                <?php
                    $paymentRateClass = $percentageOnTime >= 90 ? 'text-green-600' : ($percentageOnTime >= 80 ? 'text-yellow-600' : 'text-red-600');
                ?>
                <p class="text-xs {{ $paymentRateClass }} mt-1">
                    {{ $onTimePayments }} on-time / {{ $latePayments }} late
                </p>
            </div>
        </div>
        
        <!-- Payment Status Breakdown -->
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Payment Status</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-green-50 p-6 rounded-lg border border-green-200 text-center">
                    <div class="text-3xl font-bold text-green-900 mb-2">{{ $onTimePayments }}</div>
                    <p class="text-sm text-green-700 mb-1">On-time Payments</p>
                    <p class="text-xs text-green-600">Paid before due date</p>
                </div>
                
                <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200 text-center">
                    <div class="text-3xl font-bold text-yellow-900 mb-2">{{ $latePayments }}</div>
                    <p class="text-sm text-yellow-700 mb-1">Late Payments</p>
                    <p class="text-xs text-yellow-600">Paid after due date</p>
                </div>
                
                <div class="bg-red-50 p-6 rounded-lg border border-red-200 text-center">
                    <div class="text-3xl font-bold text-red-900 mb-2">{{ $overduePayments }}</div>
                    <p class="text-sm text-red-700 mb-1">Overdue Payments</p>
                    <p class="text-xs text-red-600">Not paid yet</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Expense Report -->
    <div id="expenseReport" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <?php
            // Extract expense report data with safe defaults
            $expensePeriod = isset($expenseReport['period']) 
                ? $expenseReport['period'] 
                : 'Current Period';
            $expenseTotal = isset($expenseReport['total_expenses']) 
                ? $expenseReport['total_expenses'] 
                : 0;
            $avgDailyExpense = isset($expenseReport['avg_daily_expense']) 
                ? $expenseReport['avg_daily_expense'] 
                : 0;
            $expenseCategories = isset($expenseReport['expenses_by_category']) 
                ? $expenseReport['expenses_by_category'] 
                : [];
            $expenseProperties = isset($expenseReport['expenses_by_property']) 
                ? $expenseReport['expenses_by_property'] 
                : [];
        ?>
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Expense Report</h3>
                <p class="text-gray-600">{{ $expensePeriod }} | All Properties</p>
            </div>
            <div class="text-sm text-gray-500">
                Generated: {{ date('M j, Y H:i') }}
            </div>
        </div>
        
        <!-- Expense Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-3xl font-bold text-red-900">${{ number_format($expenseTotal, 2) }}</div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-red-600 text-lg"></i>
                    </div>
                </div>
                <p class="text-sm text-red-700 mb-1">Total Expenses</p>
                <p class="text-xs text-red-600">This month</p>
            </div>
            
            <div class="bg-orange-50 rounded-lg p-6 border border-orange-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-3xl font-bold text-orange-900">${{ number_format($avgDailyExpense, 2) }}</div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-day text-orange-600 text-lg"></i>
                    </div>
                </div>
                <p class="text-sm text-orange-700 mb-1">Average Daily Expense</p>
                <p class="text-xs text-orange-600">Based on {{ now()->day }} days</p>
            </div>
            
            <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-3xl font-bold text-purple-900">{{ count($expenseCategories) }}</div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tags text-purple-600 text-lg"></i>
                    </div>
                </div>
                <p class="text-sm text-purple-700 mb-1">Expense Categories</p>
                <p class="text-xs text-purple-600">Different types of expenses</p>
            </div>
        </div>
        
        <!-- Expenses by Category -->
        @if(!empty($expenseCategories))
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Expenses by Category</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Change</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($expenseCategories as $expense)
                        <?php
                            $expenseAmount = isset($expense['amount']) ? $expense['amount'] : 0;
                            $expensePercentage = isset($expense['percentage']) ? $expense['percentage'] : 0;
                            $expenseChange = isset($expense['change']) ? $expense['change'] : 0;
                            $expenseCategory = isset($expense['category']) ? $expense['category'] : 'Unknown Category';
                            
                            $changeClass = $expenseChange >= 0 ? 'text-red-600' : 'text-green-600';
                            $changeSign = $expenseChange >= 0 ? '+' : '';
                        ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $expenseCategory }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">${{ number_format($expenseAmount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                        <div class="h-2.5 rounded-full bg-blue-500" style="width: {{ min($expensePercentage, 100) }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600">{{ number_format($expensePercentage, 1) }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm {{ $changeClass }}">
                                    {{ $changeSign }}{{ $expenseChange }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${{ number_format($expenseTotal, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">100%</td>
                            <td class="px-6 py-4 whitespace-nowrap"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif
        
        <!-- Expenses by Property -->
        @if(!empty($expenseProperties))
        <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Expenses by Property</h4>
            <div class="space-y-4">
                @foreach($expenseProperties as $property)
                <?php
                    $propertyName = isset($property['property']) ? $property['property'] : 'Unknown Property';
                    $propertyTotal = isset($property['total']) ? $property['total'] : 0;
                    $maintenanceCost = isset($property['maintenance']) ? $property['maintenance'] : 0;
                    $utilitiesCost = isset($property['utilities']) ? $property['utilities'] : 0;
                    $taxesCost = isset($property['taxes']) ? $property['taxes'] : 0;
                    $otherCost = isset($property['other']) ? $property['other'] : 0;
                ?>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="font-medium text-gray-900">{{ $propertyName }}</h5>
                        <span class="text-lg font-bold text-gray-900">${{ number_format($propertyTotal, 2) }}</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-sm font-semibold text-gray-700">Maintenance</div>
                            <div class="text-lg font-bold text-blue-600">${{ number_format($maintenanceCost, 2) }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm font-semibold text-gray-700">Utilities</div>
                            <div class="text-lg font-bold text-green-600">${{ number_format($utilitiesCost, 2) }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm font-semibold text-gray-700">Taxes</div>
                            <div class="text-lg font-bold text-yellow-600">${{ number_format($taxesCost, 2) }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm font-semibold text-gray-700">Other</div>
                            <div class="text-lg font-bold text-purple-600">${{ number_format($otherCost, 2) }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide custom date range
        const dateRangeSelect = document.getElementById('dateRange');
        const customDateRange = document.getElementById('customDateRange');
        
        if (dateRangeSelect && customDateRange) {
            dateRangeSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customDateRange.classList.remove('hidden');
                } else {
                    customDateRange.classList.add('hidden');
                }
            });
        }
        
        // Expense Chart for Financial Report
        const expenseCtx = document.getElementById('expenseChart');
        if (expenseCtx) {
            try {
                // Chart data will be loaded if available
                const chartData = {
                    labels: <?php echo json_encode($chartData['labels']); ?>,
                    data: <?php echo json_encode($chartData['data']); ?>,
                    colors: <?php echo json_encode($chartData['colors']); ?>
                };
                
                if (chartData.data && chartData.data.length > 0) {
                    new Chart(expenseCtx.getContext('2d'), {
                        type: 'pie',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                data: chartData.data,
                                backgroundColor: chartData.colors,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = Math.round((value / total) * 100);
                                            return `${label}: $${value.toLocaleString()} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading chart:', error);
            }
        }
        
        // Populate property dropdown with real properties
        const propertySelect = document.getElementById('propertySelect');
        if (propertySelect) {
            const existingOptions = Array.from(propertySelect.options).map(opt => opt.value);
            
            // Add properties that aren't already in the dropdown
            @foreach($properties as $property)
                <?php
                    $propertyName = isset($property['name']) ? $property['name'] : 'Unknown Property';
                    $propertySlug = Str::slug($propertyName);
                ?>
                if (!existingOptions.includes('{{ $propertySlug }}')) {
                    const option = new Option('{{ $propertyName }}', '{{ $propertySlug }}');
                    propertySelect.add(option);
                }
            @endforeach
        }
    });
    
    function generateReport() {
        const reportType = document.getElementById('reportType').value;
        
        // Hide all reports
        const reportIds = ['financialReport', 'occupancyReport', 'maintenanceReport', 'tenantReport', 'expenseReport'];
        reportIds.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.classList.add('hidden');
            }
        });
        
        // Show selected report
        const selectedReport = document.getElementById(`${reportType}Report`);
        if (selectedReport) {
            selectedReport.classList.remove('hidden');
        }
        
        console.log('Generating report:', reportType);
    }
    
    function printReport() {
        const reportType = document.getElementById('reportType').value;
        const reportElement = document.getElementById(`${reportType}Report`);
        
        if (reportElement) {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>${document.title}</title>
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
                    <script src="https://cdn.tailwindcss.com"><\/script>
                    <style>
                        @media print {
                            body { margin: 0; padding: 20px; }
                            .no-print { display: none !important; }
                            @page { margin: 0; }
                        }
                    </style>
                </head>
                <body>
                    ${reportElement.outerHTML}
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(function() {
                                window.close();
                            }, 1000);
                        }
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
    }
    
    function exportToPDF() {
        const reportType = document.getElementById('reportType').value;
        alert(`PDF export for ${reportType} report would be implemented here`);
    }
</script>
@endpush

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .report-container, .report-container * {
            visibility: visible;
        }
        .report-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none;
            border: none;
            margin: 0;
            padding: 20px;
        }
        .no-print {
            display: none !important;
        }
        button, select, input {
            display: none !important;
        }
    }
{
            position: relative;
    }
</style>
@endpush