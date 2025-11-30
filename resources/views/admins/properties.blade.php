@extends('layouts.admin')

@section('title', 'Properties - SmartRent')
@section('page-title', 'Available Properties')
@section('page-description', 'Browse all available rental properties.')

@section('content')
    <!-- Search and Filter Bar -->
    <div class="flex items-center justify-between mb-6">
        <div class="relative flex-1 max-w-md">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Search properties..." id="searchInput" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div class="flex items-center space-x-2 ml-4">
            <i class="fas fa-filter text-gray-400"></i>
            <select id="typeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">All Types</option>
                <option value="apartment">Apartment</option>
                <option value="condo">Condominium</option>
                <option value="townhouse">Townhouse</option>
                <option value="single-family">Single Family</option>
            </select>
        </div>
    </div>

    <!-- Properties Grid -->
    <div id="propertiesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($properties as $property)
        <div class="property-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" 
             data-name="{{ $property->property_name }}" 
             data-type="{{ $property->property_type }}" 
             data-location="{{ $property->property_address }}">
            <div class="relative">
                <img src="{{ asset('storage/' . $property->property_image) }}" alt="{{ $property->property_name }}" class="w-full h-48 object-cover">
                <span class="absolute top-3 right-3 bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium capitalize">
                    {{ $property->property_type }}
                </span>
            </div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $property->property_name }}</h3>
                </div>
                
                <!-- Landlord Information -->
                <div class="flex items-center mb-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            @if($property->landlord)
                                {{ $property->landlord->first_name }} 
                                {{ $property->landlord->middle_name ? $property->landlord->middle_name[0] . '.' : '' }} 
                                {{ $property->landlord->last_name }}
                            @else
                                Landlord
                            @endif
                        </p>
                        <p class="text-xs text-gray-500">Property Owner</p>
                    </div>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                        <span>{{ $property->property_address }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-building w-4 mr-2"></i>
                            <span>{{ $property->units_count ?? 0 }} units</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users w-4 mr-2"></i>
                            <span>{{ $property->occupied_units ?? 0 }} occupied</span>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-plug w-4 mr-2"></i>
                        <span>{{ $property->online_devices ?? 0 }}/{{ $property->devices_count ?? 0 }} devices online</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mb-4">
                    <div class="text-lg font-semibold text-gray-900">₱{{ number_format($property->property_price, 2) }}/month</div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Availability</div>
                        <div class="text-sm font-semibold {{ ($property->units_count - $property->occupied_units) > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $property->units_count - $property->occupied_units }} available
                        </div>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <button onclick="openViewModal({{ $property->prop_id }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-eye text-sm"></i>
                        <span>View Details</span>
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
    <!-- View Property Modal -->
    <div id="viewPropertyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
            <!-- Modal content will be populated by JavaScript -->
        </div>
    </div>
@endpush

@push('scripts')
<script>
// Global variables
let currentPropertyId = null;

// Rental functions
function rentProperty(propertyId) {
    currentPropertyId = propertyId;
    
    const propertyCard = document.querySelector(`[data-property-id="${propertyId}"]`).closest('.property-card');
    const propertyName = propertyCard.querySelector('h3').textContent;
    const propertyPrice = propertyCard.querySelector('.text-lg').textContent;
    
    // Extract price number
    const priceMatch = propertyPrice.match(/₱([\d,]+\.?\d*)/);
    const monthlyRent = priceMatch ? parseFloat(priceMatch[1].replace(/,/g, '')) : 0;
    const deposit = monthlyRent;
    
    // Populate confirmation modal
    document.getElementById('confirmPropertyName').textContent = propertyName;
    document.getElementById('confirmPropertyPrice').textContent = propertyPrice;
    document.getElementById('confirmRentAmount').textContent = propertyPrice;
    document.getElementById('confirmDepositAmount').textContent = `₱${deposit.toLocaleString()}`;
    document.getElementById('confirmTotalAmount').textContent = `₱${(monthlyRent + deposit).toLocaleString()}`;
}
// View modal functions
function openViewModal(propertyId) {
    fetch(`/admin/properties/${propertyId}`)
        .then(response => response.json())
        .then(data => {
            const modal = document.getElementById('viewPropertyModal');
            
            // Format smart devices section
            let smartDevicesHTML = '';
            if (data.smart_devices && data.smart_devices.length > 0) {
                smartDevicesHTML = `
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-plug text-blue-600 mr-2"></i>
                            Smart Devices
                            <span class="ml-2 text-sm font-normal text-gray-500">(${data.smart_devices.length} devices)</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${data.smart_devices.map(device => `
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 capitalize">${device.device_name}</h4>
                                            <p class="text-sm text-gray-500 capitalize">${device.device_type}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${device.connection_status === 'online' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                            ${device.connection_status}
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-2 text-sm">
                                        ${device.model ? `
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Model:</span>
                                                <span class="font-medium">${device.model}</span>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            } else {
                smartDevicesHTML = `
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-plug text-blue-600 mr-2"></i>
                            Smart Devices
                        </h3>
                        <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                            <i class="fas fa-plug text-gray-400 text-3xl mb-2"></i>
                            <p class="text-gray-500">No smart devices available</p>
                            <p class="text-sm text-gray-400 mt-1">This property doesn't have any smart devices yet</p>
                        </div>
                    </div>
                `;
            }

            modal.innerHTML = `
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">${data.property_name}</h2>
                        <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="p-6">
                        <img src="/storage/${data.property_image}" alt="${data.property_name}" class="w-full h-64 object-cover rounded-lg mb-6">
                        
                        <!-- Property Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-blue-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-blue-600">${data.units_count || 0}</div>
                                <div class="text-sm text-blue-600">Total Units</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-green-600">${data.units_count - data.occupied_units || 0}</div>
                                <div class="text-sm text-green-600">Available</div>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-purple-600">${data.smart_devices ? data.smart_devices.length : 0}</div>
                                <div class="text-sm text-purple-600">Smart Devices</div>
                            </div>
                            <div class="bg-orange-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-orange-600">${data.smart_devices ? data.smart_devices.filter(d => d.connection_status === 'online').length : 0}</div>
                                <div class="text-sm text-orange-600">Online Now</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            <!-- Property Details -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 flex items-center">
                                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                    Property Details
                                </h3>
                                <div class="space-y-3 bg-gray-50 rounded-lg p-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Type:</span>
                                        <span class="font-medium capitalize">${data.property_type}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Price:</span>
                                        <span class="font-medium">₱${Number(data.property_price).toLocaleString()}/month</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Availability:</span>
                                        <span class="font-medium">${data.units_count - data.occupied_units} of ${data.units_count} units</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Occupancy Rate:</span>
                                        <span class="font-medium ${(data.occupied_units / data.units_count * 100) > 80 ? 'text-green-600' : 'text-orange-600'}">
                                            ${data.units_count > 0 ? Math.round((data.occupied_units / data.units_count) * 100) : 0}%
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 flex items-center">
                                    <i class="fas fa-user text-blue-600 mr-2"></i>
                                    Contact Information
                                </h3>
                                <div class="space-y-4">
                                    <!-- Landlord Info -->
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                ${data.landlord ? `${data.landlord.first_name} ${data.landlord.middle_name ? data.landlord.middle_name[0] + '.' : ''} ${data.landlord.last_name}` : 'Landlord'}
                                            </p>
                                            <p class="text-sm text-gray-500">Property Owner</p>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-envelope text-gray-400 text-sm w-4"></i>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900 break-all">
                                                    ${data.landlord && data.landlord.email ? 
                                                        `<a href="mailto:${data.landlord.email}" class="hover:text-blue-600 transition-colors">${data.landlord.email}</a>` : 
                                                        '<span class="text-gray-400">N/A</span>'
                                                    }
                                                </p>
                                                ${data.landlord && data.landlord.email ? 
                                                    `<button onclick="copyToClipboard('${data.landlord.email}')" class="ml-2 text-gray-400 hover:text-blue-600 transition-colors" title="Copy email">
                                                        <i class="fas fa-copy text-xs"></i>
                                                    </button>` : ''
                                                }
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-phone text-gray-400 text-sm w-4"></i>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">
                                                ${data.landlord && data.landlord.phone_num ? 
                                                    `<a href="tel:${data.landlord.phone_num}" class="hover:text-blue-600 transition-colors">${data.landlord.phone_num}</a>` : 
                                                    '<span class="text-gray-400">N/A</span>'
                                                }
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Property Description -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <i class="fas fa-clipboard text-blue-600 mr-2"></i>
                                Description
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-700">${data.property_description}</p>
                            </div>
                        </div>

                        <!-- Smart Devices Section -->
                        ${smartDevicesHTML}
                    </div>
                </div>
            `;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        })
        .catch(error => {
            console.error('Error fetching property details:', error);
            showNotification('Failed to load property details', 'error');
        });
}

function closeViewModal() {
    document.getElementById('viewPropertyModal').classList.add('hidden');
    document.getElementById('viewPropertyModal').classList.remove('flex');
}

// Utility functions
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check text-xs text-green-600"></i>';
        button.classList.remove('hover:text-blue-600');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.add('hover:text-blue-600');
        }, 2000);
    }).catch(function(err) {
        console.error('Failed to copy text: ', err);
        showNotification('Failed to copy to clipboard', 'error');
    });
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    document.querySelectorAll('.custom-notification').forEach(notification => notification.remove());
    
    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-transform duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Rental confirmation
    document.getElementById('confirmRentBtn').addEventListener('click', function() {
        if (!currentPropertyId) return;
        
        const button = this;
        const originalText = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i><span>Processing...</span>';
        button.disabled = true;
        
        // Send rental request
        fetch(`/admin/properties/${currentPropertyId}/rent`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    });

    // Search and filter functionality
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const propertyCards = document.querySelectorAll('.property-card');
    const noResults = document.getElementById('noResults');
    
    function filterProperties() {
        const searchTerm = searchInput.value.toLowerCase();
        const typeValue = typeFilter.value;
        let visibleCount = 0;
        
        propertyCards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const type = card.dataset.type;
            const location = card.dataset.location.toLowerCase();
            
            const matchesSearch = name.includes(searchTerm) || location.includes(searchTerm);
            const matchesType = !typeValue || type === typeValue;
            
            if (matchesSearch && matchesType) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }
    
    searchInput.addEventListener('input', filterProperties);
    typeFilter.addEventListener('change', filterProperties);
});
</script>
@endpush