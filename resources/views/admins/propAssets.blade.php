@extends('layouts.admin')

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
                    <p class="text-3xl font-bold text-gray-900">8</p>
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
                    <p class="text-3xl font-bold text-gray-900">7</p>
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
                    <p class="text-3xl font-bold text-gray-900">6</p>
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
                    <p class="text-3xl font-bold text-gray-900">1</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Devices Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Smart Thermostat -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-thermometer-half text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Smart Thermostat</h3>
                        <p class="text-sm text-gray-500">Sunset Villa #12</p>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-green-600 font-medium">Online</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-full bg-green-200 rounded-full h-2 w-16">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                    <span class="text-xs text-gray-600">85%</span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600">Status:</span>
                <span class="text-sm font-medium text-gray-900">72°F</span>
            </div>

            <button class="w-full bg-red-50 text-red-600 py-2 px-4 rounded-lg font-medium hover:bg-red-100 transition-colors flex items-center justify-center space-x-2">
                <i class="fas fa-power-off text-sm"></i>
                <span>Turn Off</span>
            </button>
        </div>

        <!-- Front Door Lock -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-lock text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Front Door Lock</h3>
                        <p class="text-sm text-gray-500">Downtown Loft #3</p>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-green-600 font-medium">Online</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-full bg-green-200 rounded-full h-2 w-16">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 65%"></div>
                    </div>
                    <span class="text-xs text-gray-600">65%</span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600">Status:</span>
                <span class="text-sm font-medium text-gray-900">Locked</span>
            </div>

            <button class="w-full bg-red-50 text-red-600 py-2 px-4 rounded-lg font-medium hover:bg-red-100 transition-colors flex items-center justify-center space-x-2">
                <i class="fas fa-power-off text-sm"></i>
                <span>Turn Off</span>
            </button>
        </div>

        <!-- Living Room Lights -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-lightbulb text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Living Room Lights</h3>
                        <p class="text-sm text-gray-500">Garden Court #15</p>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-green-600 font-medium">Online</span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600">Status:</span>
                <span class="text-sm font-medium text-gray-900">80% Brightness</span>
            </div>

            <button class="w-full bg-red-50 text-red-600 py-2 px-4 rounded-lg font-medium hover:bg-red-100 transition-colors flex items-center justify-center space-x-2">
                <i class="fas fa-power-off text-sm"></i>
                <span>Turn Off</span>
            </button>
        </div>

        <!-- Security Camera -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-video text-gray-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Security Camera</h3>
                        <p class="text-sm text-gray-500">Riverside Manor #8</p>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                    <span class="text-sm text-red-600 font-medium">Offline</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-full bg-yellow-200 rounded-full h-2 w-16">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                    <span class="text-xs text-gray-600">45%</span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600">Status:</span>
                <span class="text-sm font-medium text-gray-900">Recording</span>
            </div>

            <button class="w-full bg-green-50 text-green-600 py-2 px-4 rounded-lg font-medium hover:bg-green-100 transition-colors flex items-center justify-center space-x-2">
                <i class="fas fa-power-off text-sm"></i>
                <span>Turn On</span>
            </button>
        </div>

        <!-- Kitchen Lights -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-lightbulb text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Kitchen Lights</h3>
                        <p class="text-sm text-gray-500">Tech Hub #22</p>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-green-600 font-medium">Online</span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600">Status:</span>
                <span class="text-sm font-medium text-gray-900">Off</span>
            </div>

            <button class="w-full bg-green-50 text-green-600 py-2 px-4 rounded-lg font-medium hover:bg-green-100 transition-colors flex items-center justify-center space-x-2">
                <i class="fas fa-power-off text-sm"></i>
                <span>Turn On</span>
            </button>
        </div>

        <!-- Smart Lock Pro -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-lock text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Smart Lock Pro</h3>
                        <p class="text-sm text-gray-500">Historic Heights #7</p>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-green-600 font-medium">Online</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-full bg-green-200 rounded-full h-2 w-16">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 92%"></div>
                    </div>
                    <span class="text-xs text-gray-600">92%</span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600">Status:</span>
                <span class="text-sm font-medium text-gray-900">Unlocked</span>
            </div>

            <button class="w-full bg-red-50 text-red-600 py-2 px-4 rounded-lg font-medium hover:bg-red-100 transition-colors flex items-center justify-center space-x-2">
                <i class="fas fa-power-off text-sm"></i>
                <span>Turn Off</span>
            </button>
        </div>

        <!-- Climate Control -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-thermometer-half text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Climate Control</h3>
                        <p class="text-sm text-gray-500">Ocean View #4</p>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-green-600 font-medium">Online</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-full bg-green-200 rounded-full h-2 w-16">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 78%"></div>
                    </div>
                    <span class="text-xs text-gray-600">78%</span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600">Status:</span>
                <span class="text-sm font-medium text-gray-900">68°F</span>
            </div>

            <button class="w-full bg-green-50 text-green-600 py-2 px-4 rounded-lg font-medium hover:bg-green-100 transition-colors flex items-center justify-center space-x-2">
                <i class="fas fa-power-off text-sm"></i>
                <span>Turn On</span>
            </button>
        </div>

        <!-- Outdoor Camera -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-video text-gray-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Outdoor Camera</h3>
                        <p class="text-sm text-gray-500">Sunset Villa #5</p>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-green-600 font-medium">Online</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-full bg-yellow-200 rounded-full h-2 w-16">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 23%"></div>
                    </div>
                    <span class="text-xs text-gray-600">23%</span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600">Status:</span>
                <span class="text-sm font-medium text-gray-900">Monitoring</span>
            </div>

            <button class="w-full bg-green-50 text-green-600 py-2 px-4 rounded-lg font-medium hover:bg-green-100 transition-colors flex items-center justify-center space-x-2">
                <i class="fas fa-power-off text-sm"></i>
                <span>Turn On</span>
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Device control functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Add device control functionality here
        const deviceButtons = document.querySelectorAll('button[class*="bg-"]:not([class*="cog"])');
        
        deviceButtons.forEach(button => {
            button.addEventListener('click', function() {
                const deviceCard = this.closest('.bg-white');
                const deviceName = deviceCard.querySelector('h3').textContent;
                const currentStatus = deviceCard.querySelector('.text-gray-900:last-of-type').textContent;
                
                // Toggle device state
                if (this.textContent.includes('Turn Off')) {
                    this.innerHTML = '<i class="fas fa-power-off text-sm"></i><span>Turn On</span>';
                    this.className = this.className.replace('bg-red-50 text-red-600', 'bg-green-50 text-green-600');
                    this.className = this.className.replace('hover:bg-red-100', 'hover:bg-green-100');
                    
                    // Update status display
                    deviceCard.querySelector('.text-gray-900:last-of-type').textContent = 'Off';
                    
                    console.log(`Turned off: ${deviceName}`);
                } else {
                    this.innerHTML = '<i class="fas fa-power-off text-sm"></i><span>Turn Off</span>';
                    this.className = this.className.replace('bg-green-50 text-green-600', 'bg-red-50 text-red-600');
                    this.className = this.className.replace('hover:bg-green-100', 'hover:bg-red-100');
                    
                    // Update status display based on device type
                    const deviceType = deviceName.toLowerCase();
                    if (deviceType.includes('thermostat') || deviceType.includes('climate')) {
                        deviceCard.querySelector('.text-gray-900:last-of-type').textContent = '72°F';
                    } else if (deviceType.includes('lock')) {
                        deviceCard.querySelector('.text-gray-900:last-of-type').textContent = 'Locked';
                    } else if (deviceType.includes('light')) {
                        deviceCard.querySelector('.text-gray-900:last-of-type').textContent = '80% Brightness';
                    } else if (deviceType.includes('camera')) {
                        deviceCard.querySelector('.text-gray-900:last-of-type').textContent = 'Monitoring';
                    }
                    
                    console.log(`Turned on: ${deviceName}`);
                }
            });
        });
    });
</script>
@endpush