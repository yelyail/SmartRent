@extends('layouts.tenants')

@section('title', 'Smart Devices - SmartRent')
@section('page-title', 'Smart Devices')
@section('page-description', 'Monitor and control smart devices across all your rented properties.')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Devices -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-mobile-alt text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Devices</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalDevices }}</p>
                </div>                           
            </div>
        </div>

        <!-- Online -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wifi text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Online</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $onlineDevices }}</p>
                </div>                           
            </div>
        </div>

        <!-- Active -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-power-off text-purple-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $activeDevices }}</p>
                </div>                           
            </div>
        </div>

        <!-- Low Battery -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Low Battery</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $lowBatteryDevices }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($smartDevices->count() > 0)
        <!-- Devices Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($smartDevices as $device)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 {{ $device->connection_status === 'online' ? 'bg-blue-100' : 'bg-gray-100' }} rounded-lg flex items-center justify-center">
                            @switch($device->device_type)
                                @case('thermostat')
                                    <i class="fas fa-thermometer-half text-blue-600"></i>
                                    @break
                                @case('camera')
                                    <i class="fas fa-video text-gray-600"></i>
                                    @break
                                @case('lock')
                                    <i class="fas fa-lock text-blue-600"></i>
                                    @break
                                @case('lights')
                                    <i class="fas fa-lightbulb text-yellow-600"></i>
                                    @break
                                @case('sensor')
                                    <i class="fas fa-broadcast-tower text-green-600"></i>
                                    @break
                                @case('doorbell')
                                    <i class="fas fa-bell text-orange-600"></i>
                                    @break
                                @case('plug')
                                    <i class="fas fa-plug text-purple-600"></i>
                                    @break
                                @case('alarm')
                                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                                    @break
                                @default
                                    <i class="fas fa-microchip text-gray-600"></i>
                            @endswitch
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $device->device_name }}</h3>
                            <p class="text-sm text-gray-500 capitalize">{{ $device->device_type }}</p>
                            <p class="text-xs text-gray-400">{{ $device->property_name }}</p>
                        </div>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
                
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 {{ $device->connection_status === 'online' ? 'bg-green-500' : 'bg-red-500' }} rounded-full"></div>
                        <span class="text-sm {{ $device->connection_status === 'online' ? 'text-green-600' : 'text-red-600' }} font-medium capitalize">
                            {{ $device->connection_status }}
                        </span>
                    </div>
                    @if($device->battery_level)
                    <div class="flex items-center space-x-1">
                        <div class="w-full {{ $device->battery_level > 20 ? 'bg-green-200' : 'bg-red-200' }} rounded-full h-2 w-16">
                            <div class="{{ $device->battery_level > 20 ? 'bg-green-500' : 'bg-red-500' }} h-2 rounded-full" style="width: {{ $device->battery_level }}%"></div>
                        </div>
                        <span class="text-xs {{ $device->battery_level > 20 ? 'text-gray-600' : 'text-red-600' }}">{{ $device->battery_level }}%</span>
                    </div>
                    @endif
                </div>

                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-gray-600">Power:</span>
                    <span class="text-sm font-medium {{ $device->power_status === 'on' ? 'text-green-600' : 'text-red-600' }} capitalize">
                        {{ $device->power_status }}
                    </span>
                </div>

                @if($device->model)
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-gray-600">Model:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $device->model }}</span>
                </div>
                @endif

                <button class="w-full {{ $device->power_status === 'on' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2 toggle-device" data-device-id="{{ $device->device_id }}">
                    <i class="fas fa-power-off text-sm"></i>
                    <span>Turn {{ $device->power_status === 'on' ? 'Off' : 'On' }}</span>
                </button>
            </div>
            @endforeach
        </div>
    @else
        <!-- No Devices Message -->
        <div class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">🏠</div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Smart Devices Available</h3>
            <p class="text-gray-500 mb-6">You don't have any smart devices in your rented properties yet.</p>
            <a href="{{ route('tenants.properties') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center space-x-2">
                <i class="fas fa-home"></i>
                <span>Browse Properties</span>
            </a>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deviceButtons = document.querySelectorAll('.toggle-device');
        
        deviceButtons.forEach(button => {
            button.addEventListener('click', function() {
                const deviceId = this.getAttribute('data-device-id');
                const deviceCard = this.closest('.bg-white');
                const deviceName = deviceCard.querySelector('h3').textContent;
                const currentPowerStatus = deviceCard.querySelector('.text-sm.font-medium.capitalize').textContent.trim();
                
                // Show loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i><span>Processing...</span>';
                this.disabled = true;

                // Simulate API call to toggle device
                setTimeout(() => {
                    // Toggle device state
                    if (currentPowerStatus === 'on') {
                        this.innerHTML = '<i class="fas fa-power-off text-sm"></i><span>Turn On</span>';
                        this.className = this.className.replace('bg-red-50 text-red-600 hover:bg-red-100', 'bg-green-50 text-green-600 hover:bg-green-100');
                        
                        // Update status display
                        deviceCard.querySelector('.text-sm.font-medium.capitalize').textContent = 'off';
                        deviceCard.querySelector('.text-sm.font-medium.capitalize').className = 'text-sm font-medium capitalize text-red-600';
                        
                        console.log(`Turned off: ${deviceName}`);
                    } else {
                        this.innerHTML = '<i class="fas fa-power-off text-sm"></i><span>Turn Off</span>';
                        this.className = this.className.replace('bg-green-50 text-green-600 hover:bg-green-100', 'bg-red-50 text-red-600 hover:bg-red-100');
                        
                        // Update status display
                        deviceCard.querySelector('.text-sm.font-medium.capitalize').textContent = 'on';
                        deviceCard.querySelector('.text-sm.font-medium.capitalize').className = 'text-sm font-medium capitalize text-green-600';
                        
                        console.log(`Turned on: ${deviceName}`);
                    }
                    
                    this.disabled = false;
                    
                    fetch(`/tenants/smart-devices/${deviceId}/toggle`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI based on response
                        } else {
                            // Revert changes if failed
                            this.innerHTML = originalText;
                        }
                        this.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.innerHTML = originalText;
                        this.disabled = false;
                    });
                    */
                    
                }, 1000);
            });
        });
    });
</script>
@endpush