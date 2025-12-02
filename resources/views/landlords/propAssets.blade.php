@extends('layouts.landlord')

@section('title', 'Smart Devices - SmartRent')
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
                </div>
                
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 {{ $device->getConnectionStatusColorClass() }} rounded-full"></div>
                        <span class="text-sm {{ $device->getConnectionStatusColorClass('text') }} font-medium capitalize">
                            {{ $device->connection_status }}
                        </span>
                    </div>
                    @if($device->battery_level !== null)
                        <div class="flex items-center space-x-1">
                            <div class="w-full bg-gray-200 rounded-full h-2 w-16">
                                <div class="{{ $device->getBatteryColorClass() }} h-2 rounded-full" 
                                    style="width: {{ $device->battery_level }}%"></div>
                            </div>
                            <span class="text-xs text-gray-600">{{ $device->battery_level }}%</span>
                        </div>
                    @endif
                </div>

                <!-- Power and Connection Controls -->
                <div class="space-y-3 mb-4">
                    <!-- Power Status Row -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Power:</span>
                            <span class="text-sm font-medium text-gray-900 capitalize">{{ $device->power_status }}</span>
                        </div>
                        <button class="connection-toggle text-xs px-3 py-1.5 rounded {{ $device->power_status === 'on' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} transition-colors"
                            data-device-id="{{ $device->device_id }}"
                            data-type="power"
                            data-current-status="{{ $device->power_status }}">
                            <i class="fas fa-power-off text-xs mr-1"></i>
                            {{ $device->power_status === 'on' ? 'Turn Off' : 'Turn On' }}
                        </button>
                    </div>

                    <!-- Connection Status Row -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Connection:</span>
                            <span class="text-sm font-medium {{ $device->getConnectionStatusColorClass('text') }} capitalize">{{ $device->connection_status }}</span>
                        </div>
                        <button class="connection-toggle text-xs px-3 py-1.5 rounded {{ $device->connection_status === 'online' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} transition-colors"
                            data-device-id="{{ $device->device_id }}"
                            data-type="connection"
                            data-current-status="{{ $device->connection_status }}">
                            <i class="fas fa-wifi text-xs mr-1"></i>
                            {{ $device->connection_status === 'online' ? 'Go Offline' : 'Go Online' }}
                        </button>
                    </div>
                </div>

                <!-- Serial Number -->
                <div class="flex items-center justify-between mb-4 pt-4 border-t border-gray-100">
                    <span class="text-sm text-gray-600">Serial:</span>
                    <span class="text-sm font-mono text-gray-900">{{ $device->serial_number }}</span>
                </div>
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
        // Handle both power and connection status toggles
        const statusButtons = document.querySelectorAll('.connection-toggle');
        
        statusButtons.forEach(button => {
            button.addEventListener('click', function() {
                const deviceId = this.getAttribute('data-device-id');
                const type = this.getAttribute('data-type'); // 'power' or 'connection'
                const currentStatus = this.getAttribute('data-current-status');
                const deviceCard = this.closest('.bg-white');
                const deviceName = deviceCard.querySelector('h3').textContent;
                
                const newStatus = currentStatus === 'on' || currentStatus === 'online' ? 
                    (type === 'power' ? 'off' : 'offline') : 
                    (type === 'power' ? 'on' : 'online');
                
                const actionText = type === 'power' ? 
                    (newStatus === 'on' ? 'on' : 'off') : 
                    (newStatus === 'online' ? 'online' : 'offline');
                
                // Show confirmation popup
                Swal.fire({
                    title: `${type === 'power' ? 'Power' : 'Connection'} Status Change`,
                    text: `Are you sure you want to turn ${actionText} this device's ${type}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: (newStatus === 'on' || newStatus === 'online') ? '#10B981' : '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: `Yes, turn ${actionText}`,
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateDeviceStatus(deviceId, type, newStatus, this, deviceCard);
                    }
                });
            });
        });
        
        // Function to update device status via AJAX
        function updateDeviceStatus(deviceId, type, status, button, deviceCard) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            // Show loading state
            const originalButtonState = {
                html: button.innerHTML,
                className: button.className,
                disabled: button.disabled
            };
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin text-xs mr-1"></i>Updating...';
            button.disabled = true;
            
            // Create FormData
            const formData = new FormData();
            if (type === 'power') {
                formData.append('power_status', status);
            } else {
                formData.append('connection_status', status);
            }
            formData.append('_token', csrfToken);
            
            // Make the request
            fetch(`/landlord/devices/${deviceId}/status`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the button and status display
                    updateStatusUI(deviceCard, type, status, button);
                    
                    // Update stats cards - ADDED THIS
                    updateStatsCards(type, status);
                    
                    // Show success message
                    Swal.fire({
                        title: 'Success!',
                        text: data.message || 'Device status updated successfully',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Refresh the page to update all stats - ADDED THIS
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Failed to update device status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Revert button
                button.innerHTML = originalButtonState.html;
                button.className = originalButtonState.className;
                button.disabled = false;
                
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Failed to update device. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
        
        // Function to update UI after status change
        function updateStatusUI(deviceCard, type, newStatus, button) {
            if (type === 'power') {
                // Find the power status elements
                const powerStatusSpan = deviceCard.querySelector('.flex.items-center.justify-between .text-sm.font-medium.text-gray-900.capitalize');
                const powerButton = deviceCard.querySelector('button[data-type="power"]');
                
                // Update status display
                if (powerStatusSpan) {
                    powerStatusSpan.textContent = newStatus;
                }
                
                // Update button
                if (powerButton) {
                    const buttonText = newStatus === 'on' ? 'Turn Off' : 'Turn On';
                    powerButton.innerHTML = '<i class="fas fa-power-off text-xs mr-1"></i>' + buttonText;
                    powerButton.className = `connection-toggle text-xs px-3 py-1.5 rounded ${newStatus === 'on' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100'} transition-colors`;
                    powerButton.setAttribute('data-current-status', newStatus);
                }
                
            } else if (type === 'connection') {
                // Find the connection status elements
                const connectionRow = deviceCard.querySelectorAll('.flex.items-center.justify-between')[1];
                const connectionSpan = connectionRow ? connectionRow.querySelector('.text-sm.font-medium') : null;
                const statusDot = deviceCard.querySelector('.flex.items-center.justify-between.mb-4 .w-2.h-2');
                const connectionButton = deviceCard.querySelector('button[data-type="connection"]');
                
                // Update status display
                if (connectionSpan) {
                    connectionSpan.textContent = newStatus;
                    connectionSpan.className = `text-sm font-medium ${newStatus === 'online' ? 'text-green-600' : 'text-red-600'} capitalize`;
                }
                
                // Update status dot
                if (statusDot) {
                    statusDot.className = `w-2 h-2 ${newStatus === 'online' ? 'bg-green-500' : 'bg-red-500'} rounded-full`;
                }
                
                // Update button
                if (connectionButton) {
                    const buttonText = newStatus === 'online' ? 'Go Offline' : 'Go Online';
                    connectionButton.innerHTML = '<i class="fas fa-wifi text-xs mr-1"></i>' + buttonText;
                    connectionButton.className = `connection-toggle text-xs px-3 py-1.5 rounded ${newStatus === 'online' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100'} transition-colors`;
                    connectionButton.setAttribute('data-current-status', newStatus);
                }
            }
            
            // Re-enable the clicked button
            button.disabled = false;
        }
        
        // Function to update stats cards - ADDED THIS
        function updateStatsCards(type, newStatus) {
            if (type === 'power') {
                const poweredOnCard = document.querySelector('.bg-white:nth-child(3) .text-3xl.font-bold');
                if (poweredOnCard) {
                    let currentCount = parseInt(poweredOnCard.textContent) || 0;
                    if (newStatus === 'on') {
                        poweredOnCard.textContent = currentCount + 1;
                    } else {
                        poweredOnCard.textContent = Math.max(0, currentCount - 1);
                    }
                }
            } else if (type === 'connection') {
                const onlineCard = document.querySelector('.bg-white:nth-child(2) .text-3xl.font-bold');
                if (onlineCard) {
                    let currentCount = parseInt(onlineCard.textContent) || 0;
                    if (newStatus === 'online') {
                        onlineCard.textContent = currentCount + 1;
                    } else {
                        onlineCard.textContent = Math.max(0, currentCount - 1);
                    }
                }
            }
        }
    });
</script>
@endpush