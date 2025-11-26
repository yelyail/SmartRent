@extends('layouts.landlord')

@section('title', 'Smart Devices - SmartRent')
@section('page-title', 'Smart Devices')
@section('page-description', 'Monitor and control smart devices across all your properties.')

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
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
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
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['online'] }}</p>
                    </div>                           
                </div>
            </div>

            <!-- Powered On -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-power-off text-purple-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Powered Status</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['active'] }}</p>
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
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['low_battery'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Devices Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($smartDevices as $device)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                @switch(strtolower($device->device_type))
                                    @case('thermostat')
                                        <i class="fas fa-thermometer-half text-blue-600"></i>
                                        @break
                                    @case('lock')
                                        <i class="fas fa-lock text-blue-600"></i>
                                        @break
                                    @case('light')
                                        <i class="fas fa-lightbulb text-yellow-600"></i>
                                        @break
                                    @case('camera')
                                        <i class="fas fa-video text-gray-600"></i>
                                        @break
                                    @case('sensor')
                                        <i class="fas fa-wave-square text-green-600"></i>
                                        @break
                                    @case('plug')
                                    @case('outlet')
                                        <i class="fas fa-plug text-purple-600"></i>
                                        @break
                                    @case('doorbell')
                                        <i class="fas fa-bell text-orange-600"></i>
                                        @break
                                    @default
                                        <i class="fas fa-microchip text-blue-600"></i>
                                @endswitch
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 capitalize">{{ $device->device_name }}</h3>
                                <p class="text-sm text-gray-500 capitalize">
                                    {{ $device->property->property_name ?? 'No Property' }}
                                </p>
                                <p class="text-xs text-gray-400 capitalize">{{ $device->device_type }} • {{ $device->model }}</p>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-cog"></i>
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 capitalize {{ $device->getConnectionStatusColorClass() }} rounded-full"></div>
                            <span class="text-sm capitalize {{ $device->getConnectionStatusColorClass('text') }} font-medium capitalize">
                                {{ $device->connection_status }}
                            </span>
                        </div>
                        @if($device->battery_level !== null)
                            <div class="flex items-center space-x-1">
                                <div class="w-full bg-gray-200 rounded-full h-2 w-16">
                                    <div class="{{ $device->getBatteryColorClass() }} h-2 rounded-full" 
                                        style="width: {{ $device->battery_level }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600 capitalize">{{ $device->battery_level }}%</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-600">Power Status:</span>
                        <span class="text-sm font-medium text-gray-900 capitalize">
                            {{ $device->power_status }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-600">Serial:</span>
                        <span class="text-sm font-mono text-gray-900 capitalize">{{ $device->serial_number }}</span>
                    </div>

                    <button class="w-full capitalize {{ $device->power_status === 'on' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} 
                            py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2 device-toggle"
                        data-device-id="{{ $device->device_id }}"
                        data-current-status="{{ $device->power_status }}">
                        <i class="fas fa-power-off text-sm"></i>
                        <span>{{ $device->power_status === 'on' ? 'Turn Off' : 'Turn On' }}</span>
                    </button>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-mobile-alt text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Smart Devices Found</h3>
                    <p class="text-gray-600 mb-6">You haven't added any smart devices to your properties yet.</p>
                    <a href="{{ route('landlords.properties') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        Manage Properties
                    </a>
                </div>
            @endforelse
        </div>
    @endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Device control functionality
        const deviceButtons = document.querySelectorAll('.device-toggle');
        
        deviceButtons.forEach(button => {
            button.addEventListener('click', function() {
                const deviceId = this.getAttribute('data-device-id');
                const currentStatus = this.getAttribute('data-current-status');
                const deviceCard = this.closest('.bg-white');
                const statusElement = deviceCard.querySelector('.text-gray-900.capitalize');
                const deviceName = deviceCard.querySelector('h3').textContent;
                
                const newStatus = currentStatus === 'on' ? 'off' : 'on';
                const actionText = newStatus === 'on' ? 'on' : 'off';
                
                // Show confirmation popup
                Swal.fire({
                    title: `Turn ${actionText.toUpperCase()} ${deviceName}?`,
                    text: `Are you sure you want to turn ${actionText} this device?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: newStatus === 'on' ? '#10B981' : '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: `Yes, turn ${actionText}`,
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Update UI immediately for better UX
                        updateUI(this, statusElement, newStatus);
                        
                        // Send AJAX request to update database
                        updateDeviceStatus(deviceId, newStatus, this, statusElement, currentStatus);
                    }
                });
            });
        });
        
        // Function to update UI
        function updateUI(button, statusElement, newStatus) {
            if (newStatus === 'off') {
                button.innerHTML = '<i class="fas fa-power-off text-sm"></i><span>Turn On</span>';
                button.className = 'w-full bg-green-50 text-green-600 hover:bg-green-100 py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2 device-toggle';
                button.setAttribute('data-current-status', 'off');
                statusElement.textContent = 'off';
            } else {
                button.innerHTML = '<i class="fas fa-power-off text-sm"></i><span>Turn Off</span>';
                button.className = 'w-full bg-red-50 text-red-600 hover:bg-red-100 py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2 device-toggle';
                button.setAttribute('data-current-status', 'on');
                statusElement.textContent = 'on';
            }
        }
        
        // Function to revert UI in case of error
        function revertUI(button, statusElement, oldStatus) {
            if (oldStatus === 'off') {
                button.innerHTML = '<i class="fas fa-power-off text-sm"></i><span>Turn On</span>';
                button.className = 'w-full bg-green-50 text-green-600 hover:bg-green-100 py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2 device-toggle';
                button.setAttribute('data-current-status', 'off');
                statusElement.textContent = 'off';
            } else {
                button.innerHTML = '<i class="fas fa-power-off text-sm"></i><span>Turn Off</span>';
                button.className = 'w-full bg-red-50 text-red-600 hover:bg-red-100 py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2 device-toggle';
                button.setAttribute('data-current-status', 'on');
                statusElement.textContent = 'on';
            }
        }
        
        // Function to update device status via AJAX
        function updateDeviceStatus(deviceId, status, button, statusElement, oldStatus) {
            // Show loading state
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i><span>Updating...</span>';
            button.disabled = true;
            
            fetch(`/landlord/devices/${deviceId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    power_status: status
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('API Response:', data); // Debug log
                
                if (data.success) {
                    // Success - update UI to final state
                    updateUI(button, statusElement, status);
                    
                    // Success notification
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    console.log(`Device ${deviceId} status updated to: ${status}`);
                } else {
                    // Revert UI changes if update failed
                    revertUI(button, statusElement, oldStatus);
                    
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to update device status',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    
                    console.error('Failed to update device status:', data.message);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                
                // Revert UI changes on error
                revertUI(button, statusElement, oldStatus);
                
                Swal.fire({
                    title: 'Network Error!',
                    text: 'Failed to connect to server. Please check your connection.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            })
            .finally(() => {
                // Always re-enable the button
                button.disabled = false;
            });
        }

        // Add click handler for settings cog button
        const settingsButtons = document.querySelectorAll('button.text-gray-400');
        settingsButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const deviceCard = this.closest('.bg-white');
                const deviceName = deviceCard.querySelector('h3').textContent;
                
                Swal.fire({
                    title: 'Device Settings',
                    text: `Settings for ${deviceName}`,
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            });
        });
    });
</script>
@endpush