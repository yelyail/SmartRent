@extends('layouts.landlord')

@section('title', 'Analytics_tenant - SmartRent')
@section('page-description', 'Your rental dashboard and analytics')

@section('content')
    <!-- Key Metrics Cards for Tenant -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Current Rent -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-home text-blue-600 text-lg"></i>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Monthly Rent</p>
                <p class="text-3xl font-bold text-gray-900">
                    ₱{{ number_format($currentLease->rent_amount ?? 0, 2) }}
                </p>
                <p class="text-xs text-gray-500 mt-1">Current lease</p>
            </div>
        </div>

       <!-- Maintenance Requests -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tools text-orange-600 text-lg"></i>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Maintenance Requests</p>
                <p class="text-3xl font-bold text-gray-900">{{ $maintenanceStats['monthly_requests'] }}</p>
                <p class="text-xs text-gray-500 mt-1">This month</p>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Pending Payments</p>
                <p class="text-3xl font-bold text-gray-900">{{ $paymentStats['pending_payments'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Require attention</p>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 text-lg"></i>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Monthly Revenue</p>
                <p class="text-3xl font-bold text-gray-900">₱{{ number_format($paymentStats['monthly_revenue'], 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">This month</p>
            </div>
        </div>
    </div>

    <!-- Active Leases Overview -->
    @if($activeLeases->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Active Leases Overview</h3>
            <span class="text-sm text-gray-600">{{ $activeLeases->count() }} active leases</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Monthly Rent -->
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-dollar-sign text-blue-600"></i>
                </div>
                <p class="text-sm text-gray-600 mb-1">Total Monthly Rent</p>
                <p class="text-2xl font-bold text-gray-900">
                    ₱{{ number_format($activeLeases->sum('rent_amount'), 2) }}
                </p>
            </div>

            <!-- Average Rent -->
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-chart-line text-green-600"></i>
                </div>
                <p class="text-sm text-gray-600 mb-1">Average Rent</p>
                <p class="text-2xl font-bold text-gray-900">
                    ₱{{ number_format($activeLeases->avg('rent_amount'), 2) }}
                </p>
            </div>

            <!-- Lease Distribution -->
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-calendar text-purple-600"></i>
                </div>
                <p class="text-sm text-gray-600 mb-1">Avg Lease Duration</p>
                <p class="text-2xl font-bold text-gray-900">
                    @php
                        $avgMonths = $activeLeases->avg(function($lease) {
                            return \Carbon\Carbon::parse($lease->start_date)->diffInMonths(\Carbon\Carbon::parse($lease->end_date));
                        });
                    @endphp
                    {{ number_format($avgMonths) }} months
                </p>
            </div>
        </div>

        <!-- Recent Active Leases -->
        <div class="mt-6">
            <h4 class="text-md font-semibold text-gray-900 mb-4">Recent Active Leases</h4>
            <div class="space-y-3">
                @foreach($activeLeases->take(3) as $lease)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $lease->user->first_name ?? 'N/A' }} {{ $lease->user->last_name ?? '' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $lease->unit->property->property_name ?? 'N/A' }} - Unit {{ $lease->unit->unit_num ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">
                            ₱{{ number_format($lease->rent_amount, 2) }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($activeLeases->count() > 3)
            <div class="mt-4 text-center">
                <a href="{{ route('landlords.analytics') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View all {{ $activeLeases->count() }} active leases
                </a>
            </div>
            @endif
        </div>
    </div>
    @else
    <!-- No Active Leases -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-file-contract text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Active Leases</h3>
            <p class="text-gray-500 mb-4">There are no active tenant leases at the moment.</p>
            <a href="{{ route('landlords.properties') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2 text-sm"></i>
                Add Properties
            </a>
        </div>
    </div>
    @endif

    <!-- Recent Maintenance Requests -->
    @if($maintenanceRequests->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Recent Maintenance Requests</h3>
            <a href="{{ route('landlords.maintenance') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                View All
            </a>
        </div>
        
        <div class="space-y-4">
            @foreach($maintenanceRequests as $request)
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-wrench text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $request->title }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $request->unit->property->property_name ?? 'N/A' }} - Unit {{ $request->unit->unit_num ?? 'N/A' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            by {{ $request->user->first_name ?? 'Tenant' }} {{ $request->user->last_name ?? '' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($request->priority == 'high' || $request->priority == 'urgent') bg-red-100 text-red-800
                        @elseif($request->priority == 'medium') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ $request->priority }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($request->status == 'pending') bg-orange-100 text-orange-800
                        @elseif($request->status == 'in_progress') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ str_replace('_', ' ', $request->status) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <!-- No Maintenance Requests -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-wrench text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Maintenance Requests</h3>
            <p class="text-gray-500">There are no maintenance requests across your properties.</p>
        </div>
    </div>
    @endif

    <!-- Recent Payments -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Recent Payments Received</h3>
            {{-- <a href="{{ route('landlords.payments') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                View All
            </a> --}}
        </div>
        
        <div class="space-y-4">
            @forelse($recentPayments as $payment)
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-receipt text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $payment->lease->user->first_name ?? 'Tenant' }} {{ $payment->lease->user->last_name ?? '' }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $payment->billing->bill_name ?? 'Rent Payment' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $payment->lease->unit->property->property_name ?? 'N/A' }} - Unit {{ $payment->lease->unit->unit_num ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">
                        ₱{{ number_format($payment->amount_paid, 2) }}
                    </p>
                    <p class="text-xs text-gray-500 capitalize">
                        {{ $payment->payment_method ?? 'N/A' }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('M j, Y') }}
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Recent Payments</h3>
                <p class="text-gray-500">No payment records found for your properties.</p>
            </div>
            @endforelse
        </div>

        <!-- Payment Summary -->
        @if($recentPayments->count() > 0)
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="grid grid-cols-2 gap-4 text-center">
                <div>
                    <p class="text-sm text-gray-600">This Month</p>
                    <p class="text-lg font-semibold text-gray-900">
                        ₱{{ number_format($paymentStats['monthly_revenue'], 2) }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Collected</p>
                    <p class="text-lg font-semibold text-gray-900">
                        ₱{{ number_format($paymentStats['total_collected'], 2) }}
                    </p>
                </div>
            </div>
        </div>
        @endif
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