@extends('layouts.landlord')

@section('title', 'Properties_landlord - SmartRent')
@section('page-description', 'Manage your rental properties and track their performance.')

@section('header-actions')
    <button id="addPropertyBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
        <i class="fas fa-plus text-sm"></i>
        <span>New Property</span>
    </button>
@endsection

@section('content')
    <!-- Search and Filter Bar -->
    <div class="flex items-center justify-between mb-6">
        <div class="relative flex-1 max-w-md">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Search properties..." id="searchInput" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div class="flex items-center space-x-2 ml-4">
            <i class="fas fa-filter text-gray-400"></i>
            <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Properties Grid -->
    <div id="propertiesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($properties as $property)
        <div class="property-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" 
             data-name="{{ $property->property_name }}" 
             data-status="{{ $property->status }}" 
             data-location="{{ $property->property_address }}">
            <div class="relative">
                <img src="{{ asset('storage/' . $property->property_image) }}" alt="{{ $property->property_name }}" class="w-full h-48 object-cover">
                <span class="absolute top-3 right-3 {{ $property->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-2 py-1 rounded-full text-xs font-medium capitalize">
                    {{ $property->status }}
                </span>
            </div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $property->property_name }}</h3>
                    <button class="text-gray-400 hover:text-gray-600 property-menu" data-property-id="{{ $property->prop_id }}">
                    </button>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                        <span>{{ $property->property_address }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-building w-4 mr-2"></i>
                            <span>{{ $property->available_units }} available</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users w-4 mr-2"></i>
                            <span>{{ $property->occupied_units ?? 0 }} occupied</span>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-home w-4 mr-2"></i>
                        <span class="capitalize">{{ $property->property_type }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="text-lg font-semibold text-gray-900">₱{{ number_format($property->property_price, 2) }}</div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Occupancy</div>
                        <div class="text-sm font-semibold text-gray-900">
                            {{ $property->units_count > 0 ? round(($property->occupied_units / $property->units_count) * 100) : 0 }}%
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button onclick="openViewModal({{ $property->prop_id }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-eye text-sm"></i>
                        <span>View</span>
                    </button>
                    <button onclick="openEditModal({{ $property->prop_id }})" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-edit text-sm"></i>
                        <span>Edit</span>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="hidden text-center py-12">
        <div class="text-gray-400 text-6xl mb-4">🏠</div>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Properties Found</h3>
        <p class="text-gray-500">Try adjusting your search criteria or filters.</p>
    </div>
@endsection

@push('modals')
    <!-- Add Property Modal -->
    <div id="addPropertyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">New Property</h2>
                        <p class="text-sm text-gray-500">Fill in the property details and add smart devices</p>
                    </div>
                </div>
                <button type="button" id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <form id="addPropertyForm" action="{{ route('landlords.properties.store') }}" method="POST" class="p-6 space-y-6" enctype="multipart/form-data">
                @csrf
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="property_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Property Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="property_name" name="property_name" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., Sunset Villa Complex"
                                value="{{ old('property_name') }}">
                            @error('property_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="property_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Property Type <span class="text-red-500">*</span>
                            </label>
                            <select id="property_type" name="property_type" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Type</option>
                                <option value="apartment" {{ old('property_type') == 'apartment' ? 'selected' : '' }}>Apartment Complex</option>
                                <option value="condo" {{ old('property_type') == 'condo' ? 'selected' : '' }}>Condominium</option>
                                <option value="townhouse" {{ old('property_type') == 'townhouse' ? 'selected' : '' }}>Townhouse</option>
                                <option value="single-family" {{ old('property_type') == 'single-family' ? 'selected' : '' }}>Single Family Home</option>
                                <option value="duplex" {{ old('property_type') == 'duplex' ? 'selected' : '' }}>Duplex</option>
                                <option value="commercial" {{ old('property_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="villa" {{ old('property_type') == 'villa' ? 'selected' : '' }}>Villa</option>
                                <option value="studio" {{ old('property_type') == 'studio' ? 'selected' : '' }}>Studio</option>
                            </select>
                            @error('property_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                        Location Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="property_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Property Address <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="property_address" name="property_address" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="123 Ocean Drive, Miami, FL 33139"
                                value="{{ old('property_address') }}">
                            @error('property_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Property Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-home text-blue-600 mr-2"></i>
                        Property Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="property_price" class="block text-sm font-medium text-gray-700 mb-2">
                                Property Price <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                <input type="number" id="property_price" name="property_price" required min="0" step="0.01"
                                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="250000.00"
                                    value="{{ old('property_price') }}">
                            </div>
                            @error('property_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="property_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Property Image <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="property_image" name="property_image" accept="image/*" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('property_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Supported formats: JPG, PNG, JPEG, GIF. Max size: 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-clipboard text-blue-600 mr-2"></i>
                        Additional Information
                    </h3>
                    <div>
                        <label for="property_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Property Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="property_description" name="property_description" rows="4" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Describe the property, its features, amenities, location advantages, and any special characteristics...">{{ old('property_description') }}</textarea>
                        @error('property_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Smart Devices Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-plug text-blue-600 mr-2"></i>
                        Smart Devices
                        <span class="ml-2 text-sm text-gray-500 font-normal">(Optional)</span>
                    </h3>
                    
                    <!-- Add Device Button -->
                    <div class="mb-4">
                        <button type="button" id="addDeviceBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                            <i class="fas fa-plus text-sm"></i>
                            <span> Smart Device</span>
                        </button>
                    </div>

                    <!-- Devices Container -->
                    <div id="devicesContainer" class="space-y-4">
                        <!-- Devices will be added here dynamically -->
                    </div>

                    <!-- No Devices Message -->
                    <div id="noDevicesMessage" class="text-center py-6 border-2 border-dashed border-gray-300 rounded-lg">
                        <i class="fas fa-plug text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500">No smart devices added yet</p>
                        <p class="text-sm text-gray-400 mt-1">Click "New Smart Device" to get started</p>
                    </div>
                </div>

                <!-- Status (Hidden field with default value) -->
                <input type="hidden" name="status" value="active">

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button type="button" id="cancelBtn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center space-x-2">
                        <i class="fas fa-plus text-sm"></i>
                        <span>New Property</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Device Template (Hidden) -->
    <template id="deviceTemplate">
        <div class="device-item bg-gray-50 border border-gray-200 rounded-lg p-4">
            <div class="flex justify-between items-start mb-4">
                <h4 class="font-semibold text-gray-900">Smart Device</h4>
                <button type="button" class="remove-device text-red-600 hover:text-red-800 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Device Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="devices[INDEX][device_name]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="e.g., Living Room Thermostat">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Device Type <span class="text-red-500">*</span>
                    </label>
                    <select name="devices[INDEX][device_type]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent device-type-select">
                        <option value="">Select Type</option>
                        <option value="thermostat">Thermostat</option>
                        <option value="camera">Security Camera</option>
                        <option value="lock">Smart Lock</option>
                        <option value="lights">Smart Lights</option>
                        <option value="sensor">Motion Sensor</option>
                        <option value="doorbell">Smart Doorbell</option>
                        <option value="plug">Smart Plug</option>
                        <option value="alarm">Alarm System</option>
                        <option value="blinds">Smart Blinds</option>
                        <option value="speaker">Smart Speaker</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Model
                    </label>
                    <input type="text" name="devices[INDEX][model]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="e.g., Nest Thermostat 3rd Gen">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Serial Number
                    </label>
                    <input type="text" name="devices[INDEX][serial_number]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="e.g., SN123456789">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Connection Status <span class="text-red-500">*</span>
                    </label>
                    <select name="devices[INDEX][connection_status]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="online">Online</option>
                        <option value="offline">Offline</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Power Status <span class="text-red-500">*</span>
                    </label>
                    <select name="devices[INDEX][power_status]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="on">On</option>
                        <option value="off">Off</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Battery Level (%)
                    </label>
                    <input type="number" name="devices[INDEX][battery_level]" min="0" max="100"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0-100">
                </div>
            </div>
        </div>
    </template>

    <!-- View Property Modal -->
    <div id="viewPropertyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
            <!-- Modal Content will be populated by JavaScript -->
        </div>
    </div>

    <!-- Edit Property Modal -->
    <div id="editPropertyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <!-- Modal Content will be populated by JavaScript -->
        </div>
    </div>
    <div class="modal fade" id="editPropertyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Loading...</h5>
            </div>
            <div class="modal-body" id="modalPropertyContent">
                <div class="text-center py-5">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
    
    <!-- Include compiled JS files -->
    <script src="{{ asset('js/properties.js') }}"></script>
    <script src="{{ asset('js/unit.js') }}"></script>
    <script src="{{ asset('js/device.js') }}"></script>
@endpush