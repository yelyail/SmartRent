@extends('layouts.staff')

@section('title', 'Properties - SmartRent')
@section('page-title', 'Properties')
@section('page-description', 'Manage your rental properties and track their performance.')

@section('header-actions')
    <button id="addPropertyBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
        <i class="fas fa-plus text-sm"></i>
        <span>Add Property</span>
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
                <option>All Status</option>
                <option>Active</option>
                <option>Maintenance</option>
                <option>Renovation</option>
            </select>
        </div>
    </div>

    <!-- Properties Grid -->
    <div id="propertiesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Property Card 1 -->
        <div class="property-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" data-name="Sunset Villa Complex" data-status="Active" data-location="Miami">
            <div class="relative">
                <img src="https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Sunset Villa Complex" class="w-full h-48 object-cover">
                <span class="absolute top-3 right-3 bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Active</span>
            </div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">Sunset Villa Complex</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                        <span>123 Ocean Drive, Miami, FL</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-building w-4 mr-2"></i>
                            <span>24 units</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users w-4 mr-2"></i>
                            <span>22 occupied</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="text-lg font-semibold text-gray-900">$2,800/month</div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Occupancy</div>
                        <div class="text-sm font-semibold text-gray-900">92%</div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button onclick="openViewModal('sunset-villa')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-eye text-sm"></i>
                        <span>View</span>
                    </button>
                    <button onclick="openEditModal('sunset-villa')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-edit text-sm"></i>
                        <span>Edit</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Property Card 2 -->
        <div class="property-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" data-name="Downtown Lofts" data-status="Active" data-location="New York">
            <div class="relative">
                <img src="https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Downtown Lofts" class="w-full h-48 object-cover">
                <span class="absolute top-3 right-3 bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Active</span>
            </div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">Downtown Lofts</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                        <span>456 Main Street, New York, NY</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-building w-4 mr-2"></i>
                            <span>18 units</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users w-4 mr-2"></i>
                            <span>16 occupied</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="text-lg font-semibold text-gray-900">$3,200/month</div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Occupancy</div>
                        <div class="text-sm font-semibold text-gray-900">89%</div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button onclick="openViewModal('downtown-lofts')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-eye text-sm"></i>
                        <span>View</span>
                    </button>
                    <button onclick="openEditModal('downtown-lofts')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-edit text-sm"></i>
                        <span>Edit</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Property Card 3 -->
        <div class="property-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" data-name="Garden Court Apartments" data-status="Active" data-location="Austin">
            <div class="relative">
                <img src="https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Garden Court Apartments" class="w-full h-48 object-cover">
                <span class="absolute top-3 right-3 bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Active</span>
            </div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">Garden Court Apartments</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                        <span>789 Elm Avenue, Austin, TX</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-building w-4 mr-2"></i>
                            <span>32 units</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users w-4 mr-2"></i>
                            <span>28 occupied</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="text-lg font-semibold text-gray-900">$1,900/month</div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Occupancy</div>
                        <div class="text-sm font-semibold text-gray-900">88%</div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button onclick="openViewModal('garden-court')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-eye text-sm"></i>
                        <span>View</span>
                    </button>
                    <button onclick="openEditModal('garden-court')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-edit text-sm"></i>
                        <span>Edit</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Property Card 4 -->
        <div class="property-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" data-name="Lakeside Residences" data-status="Maintenance" data-location="Seattle">
            <div class="relative">
                <img src="https://images.pexels.com/photos/1396132/pexels-photo-1396132.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Lakeside Residences" class="w-full h-48 object-cover">
                <span class="absolute top-3 right-3 bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">Maintenance</span>
            </div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">Lakeside Residences</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                        <span>321 Lake View Drive, Seattle, WA</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-building w-4 mr-2"></i>
                            <span>16 units</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users w-4 mr-2"></i>
                            <span>14 occupied</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="text-lg font-semibold text-gray-900">$2,400/month</div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Occupancy</div>
                        <div class="text-sm font-semibold text-gray-900">88%</div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button onclick="openViewModal('lakeside-residences')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-eye text-sm"></i>
                        <span>View</span>
                    </button>
                    <button onclick="openEditModal('lakeside-residences')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-edit text-sm"></i>
                        <span>Edit</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Property Card 5 -->
        <div class="property-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" data-name="Urban Heights" data-status="Active" data-location="Chicago">
            <div class="relative">
                <img src="https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Urban Heights" class="w-full h-48 object-cover">
                <span class="absolute top-3 right-3 bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Active</span>
            </div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">Urban Heights</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                        <span>654 Broadway, Chicago, IL</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-building w-4 mr-2"></i>
                            <span>28 units</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users w-4 mr-2"></i>
                            <span>26 occupied</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="text-lg font-semibold text-gray-900">$2,600/month</div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Occupancy</div>
                        <div class="text-sm font-semibold text-gray-900">93%</div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button onclick="openViewModal('urban-heights')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-eye text-sm"></i>
                        <span>View</span>
                    </button>
                    <button onclick="openEditModal('urban-heights')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-edit text-sm"></i>
                        <span>Edit</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Property Card 6 -->
        <div class="property-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" data-name="Riverside Commons" data-status="Renovation" data-location="Portland">
            <div class="relative">
                <img src="https://images.pexels.com/photos/1571453/pexels-photo-1571453.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Riverside Commons" class="w-full h-48 object-cover">
                <span class="absolute top-3 right-3 bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">Renovation</span>
            </div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">Riverside Commons</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                        <span>987 River Road, Portland, OR</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-building w-4 mr-2"></i>
                            <span>20 units</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users w-4 mr-2"></i>
                            <span>15 occupied</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="text-lg font-semibold text-gray-900">$2,200/month</div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Occupancy</div>
                        <div class="text-sm font-semibold text-gray-900">75%</div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button onclick="openViewModal('riverside-commons')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-eye text-sm"></i>
                        <span>View</span>
                    </button>
                    <button onclick="openEditModal('riverside-commons')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-edit text-sm"></i>
                        <span>Edit</span>
                    </button>
                </div>
            </div>
        </div>
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
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Add New Property</h2>
                        <p class="text-sm text-gray-500">Fill in the property details below</p>
                    </div>
                </div>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <form id="addPropertyForm" class="p-6 space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Property Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="propertyName" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., Sunset Villa Complex">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Property Type <span class="text-red-500">*</span>
                            </label>
                            <select id="propertyType" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Type</option>
                                <option value="apartment">Apartment Complex</option>
                                <option value="condo">Condominium</option>
                                <option value="townhouse">Townhouse</option>
                                <option value="single-family">Single Family Home</option>
                                <option value="duplex">Duplex</option>
                                <option value="commercial">Commercial</option>
                            </select>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Street Address <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="streetAddress" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="123 Ocean Drive">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="city" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Miami">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    State <span class="text-red-500">*</span>
                                </label>
                                <select id="state" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select State</option>
                                    <option value="FL">Florida</option>
                                    <option value="NY">New York</option>
                                    <option value="TX">Texas</option>
                                    <option value="CA">California</option>
                                    <option value="WA">Washington</option>
                                    <option value="IL">Illinois</option>
                                    <option value="OR">Oregon</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    ZIP Code <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="zipCode" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="33139">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Property Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-home text-blue-600 mr-2"></i>
                        Property Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Total Units <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="totalUnits" required min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="24">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Year Built
                            </label>
                            <input type="number" id="yearBuilt" min="1800" max="2024"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="2018">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Square Footage
                            </label>
                            <input type="number" id="squareFootage" min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="15000">
                        </div>
                    </div>
                </div>

                <!-- Financial Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-dollar-sign text-blue-600 mr-2"></i>
                        Financial Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Average Rent per Unit <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" id="averageRent" required min="0" step="0.01"
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="2800.00">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Security Deposit <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" id="securityDeposit" required min="0" step="0.01"
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="2800.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amenities -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-star text-blue-600 mr-2"></i>
                        Amenities
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="parking" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Parking</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="pool" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Swimming Pool</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="gym" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Fitness Center</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="laundry" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Laundry</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="elevator" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Elevator</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="balcony" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Balcony</span>
                        </label>
                    </div>
                </div>

                <!-- Additional Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-clipboard text-blue-600 mr-2"></i>
                        Additional Information
                    </h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Property Description
                        </label>
                        <textarea id="propertyDescription" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Describe the property, its features, and any special characteristics..."></textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button type="button" id="cancelBtn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center space-x-2">
                        <i class="fas fa-plus text-sm"></i>
                        <span>Add Property</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Property Modal -->
    <div id="viewPropertyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-eye text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h2 id="viewModalTitle" class="text-xl font-bold text-gray-900">Property Details</h2>
                        <p class="text-sm text-gray-500">Complete property information</p>
                    </div>
                </div>
                <button id="closeViewModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6">
                <!-- Property Images -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-images text-blue-600 mr-2"></i>
                        Property Images
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="propertyImages">
                        <!-- Images will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Property Information Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Basic Information
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Property Name:</span>
                                <span id="viewPropertyName" class="font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Type:</span>
                                <span id="viewPropertyType" class="font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span id="viewPropertyStatus" class="px-2 py-1 rounded-full text-xs font-medium"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Address:</span>
                                <span id="viewPropertyAddress" class="font-medium text-gray-900 text-right"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Property Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-home text-blue-600 mr-2"></i>
                            Property Details
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Units:</span>
                                <span id="viewTotalUnits" class="font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Occupied Units:</span>
                                <span id="viewOccupiedUnits" class="font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Occupancy Rate:</span>
                                <span id="viewOccupancyRate" class="font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Average Rent:</span>
                                <span id="viewAverageRent" class="font-medium text-gray-900"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-star text-blue-600 mr-2"></i>
                        Amenities
                    </h3>
                    <div id="viewAmenities" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        <!-- Amenities will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-clipboard text-blue-600 mr-2"></i>
                        Description
                    </h3>
                    <p id="viewDescription" class="text-gray-600 leading-relaxed"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Property Modal -->
    <div id="editPropertyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-edit text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h2 id="editModalTitle" class="text-xl font-bold text-gray-900">Edit Property</h2>
                        <p class="text-sm text-gray-500">Update property information</p>
                    </div>
                </div>
                <button id="closeEditModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <form id="editPropertyForm" class="p-6 space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Property Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="editPropertyName" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Property Type <span class="text-red-500">*</span>
                            </label>
                            <select id="editPropertyType" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="apartment">Apartment Complex</option>
                                <option value="condo">Condominium</option>
                                <option value="townhouse">Townhouse</option>
                                <option value="single-family">Single Family Home</option>
                                <option value="duplex">Duplex</option>
                                <option value="commercial">Commercial</option>
                            </select>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Street Address <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="editStreetAddress" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="editCity" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    State <span class="text-red-500">*</span>
                                </label>
                                <select id="editState" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="FL">Florida</option>
                                    <option value="NY">New York</option>
                                    <option value="TX">Texas</option>
                                    <option value="CA">California</option>
                                    <option value="WA">Washington</option>
                                    <option value="IL">Illinois</option>
                                    <option value="OR">Oregon</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    ZIP Code <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="editZipCode" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Total Units <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="editTotalUnits" required min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Average Rent <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" id="editAverageRent" required min="0" step="0.01"
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-flag text-blue-600 mr-2"></i>
                        Property Status
                    </h3>
                    <select id="editPropertyStatus" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Active">Active</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Renovation">Renovation</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-clipboard text-blue-600 mr-2"></i>
                        Description
                    </h3>
                    <textarea id="editDescription" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Property description..."></textarea>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button type="button" id="cancelEditBtn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center space-x-2">
                        <i class="fas fa-save text-sm"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endpush

@push('scripts')
<script>
    // Property data
    const propertyData = {
        'sunset-villa': {
            name: 'Sunset Villa Complex',
            type: 'Apartment Complex',
            status: 'Active',
            address: '123 Ocean Drive, Miami, FL 33139',
            totalUnits: 24,
            occupiedUnits: 22,
            occupancyRate: '92%',
            averageRent: '$2,800',
            description: 'Luxury beachfront apartment complex with stunning ocean views. Features modern amenities, private beach access, and premium finishes throughout. Perfect for discerning tenants seeking upscale coastal living.',
            amenities: ['Ocean View', 'Private Beach', 'Pool', 'Gym', 'Parking', 'Concierge'],
            images: [
                'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1396132/pexels-photo-1396132.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1571453/pexels-photo-1571453.jpeg?auto=compress&cs=tinysrgb&w=800'
            ]
        },
        'downtown-lofts': {
            name: 'Downtown Lofts',
            type: 'Loft Complex',
            status: 'Active',
            address: '456 Main Street, New York, NY 10001',
            totalUnits: 18,
            occupiedUnits: 16,
            occupancyRate: '89%',
            averageRent: '$3,200',
            description: 'Modern loft-style apartments in the heart of downtown. High ceilings, exposed brick, and industrial design elements create a unique urban living experience.',
            amenities: ['High Ceilings', 'Exposed Brick', 'Rooftop Terrace', 'Gym', 'Parking', 'Pet Friendly'],
            images: [
                'https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1396132/pexels-photo-1396132.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1571453/pexels-photo-1571453.jpeg?auto=compress&cs=tinysrgb&w=800'
            ]
        },
        'garden-court': {
            name: 'Garden Court Apartments',
            type: 'Apartment Complex',
            status: 'Active',
            address: '789 Elm Avenue, Austin, TX 78701',
            totalUnits: 32,
            occupiedUnits: 28,
            occupancyRate: '88%',
            averageRent: '$1,900',
            description: 'Family-friendly apartment complex surrounded by beautiful gardens and green spaces. Features spacious units, playground, and community amenities.',
            amenities: ['Garden Views', 'Playground', 'Pool', 'Laundry', 'Parking', 'Pet Park'],
            images: [
                'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1396132/pexels-photo-1396132.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1571453/pexels-photo-1571453.jpeg?auto=compress&cs=tinysrgb&w=800'
            ]
        },
        'lakeside-residences': {
            name: 'Lakeside Residences',
            type: 'Residential Complex',
            status: 'Maintenance',
            address: '321 Lake View Drive, Seattle, WA 98101',
            totalUnits: 16,
            occupiedUnits: 14,
            occupancyRate: '88%',
            averageRent: '$2,400',
            description: 'Serene lakeside living with panoramic water views. Currently undergoing maintenance upgrades to enhance resident experience.',
            amenities: ['Lake View', 'Dock Access', 'Gym', 'Parking', 'Storage', 'Balconies'],
            images: [
                'https://images.pexels.com/photos/1396132/pexels-photo-1396132.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1571453/pexels-photo-1571453.jpeg?auto=compress&cs=tinysrgb&w=800'
            ]
        },
        'urban-heights': {
            name: 'Urban Heights',
            type: 'High-Rise Complex',
            status: 'Active',
            address: '654 Broadway, Chicago, IL 60601',
            totalUnits: 28,
            occupiedUnits: 26,
            occupancyRate: '93%',
            averageRent: '$2,600',
            description: 'Modern high-rise living in the heart of the city. Floor-to-ceiling windows offer spectacular city views and premium amenities throughout.',
            amenities: ['City Views', 'Rooftop Deck', 'Gym', 'Concierge', 'Parking', 'Business Center'],
            images: [
                'https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1396132/pexels-photo-1396132.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1571453/pexels-photo-1571453.jpeg?auto=compress&cs=tinysrgb&w=800'
            ]
        },
        'riverside-commons': {
            name: 'Riverside Commons',
            type: 'Apartment Complex',
            status: 'Renovation',
            address: '987 River Road, Portland, OR 97201',
            totalUnits: 20,
            occupiedUnits: 15,
            occupancyRate: '75%',
            averageRent: '$2,200',
            description: 'Charming riverside apartments currently undergoing renovation to modernize units and common areas. Beautiful river views and peaceful setting.',
            amenities: ['River Views', 'Walking Trails', 'Parking', 'Storage', 'Laundry', 'Garden'],
            images: [
                'https://images.pexels.com/photos/1571453/pexels-photo-1571453.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1396132/pexels-photo-1396132.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg?auto=compress&cs=tinysrgb&w=800',
                'https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg?auto=compress&cs=tinysrgb&w=800'
            ]
        }
    };

    // Filter Properties Function
    function filterProperties() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const propertyCards = document.querySelectorAll('.property-card');
        const noResults = document.getElementById('noResults');
        let visibleCount = 0;

        propertyCards.forEach(card => {
            const propertyName = card.getAttribute('data-name').toLowerCase();
            const propertyStatus = card.getAttribute('data-status');
            const propertyLocation = card.getAttribute('data-location').toLowerCase();
            
            // Check if search matches name or location
            const matchesSearch = propertyName.includes(searchInput) || 
                                propertyLocation.includes(searchInput) ||
                                searchInput === '';
            
            // Check if status matches filter
            const matchesStatus = statusFilter === '' || propertyStatus === statusFilter;
            
            // Show/hide card based on both criteria
            if (matchesSearch && matchesStatus) {
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

    // Modal functionality
    const addPropertyBtn = document.getElementById('addPropertyBtn');
    const addPropertyModal = document.getElementById('addPropertyModal');
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const addPropertyForm = document.getElementById('addPropertyForm');

    // Open modal
    addPropertyBtn.addEventListener('click', () => {
        addPropertyModal.classList.remove('hidden');
        addPropertyModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    });

    // Close modal function
    function closeModalFunction() {
        addPropertyModal.classList.add('hidden');
        addPropertyModal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        addPropertyForm.reset();
    }

    // Close modal events
    closeModal.addEventListener('click', closeModalFunction);
    cancelBtn.addEventListener('click', closeModalFunction);

    // Close modal when clicking outside
    addPropertyModal.addEventListener('click', (e) => {
        if (e.target === addPropertyModal) {
            closeModalFunction();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !addPropertyModal.classList.contains('hidden')) {
            closeModalFunction();
        }
    });

    // Form submission
    addPropertyForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Collect form data
        const formData = {
            propertyName: document.getElementById('propertyName').value,
            propertyType: document.getElementById('propertyType').value,
            streetAddress: document.getElementById('streetAddress').value,
            city: document.getElementById('city').value,
            state: document.getElementById('state').value,
            zipCode: document.getElementById('zipCode').value,
            totalUnits: document.getElementById('totalUnits').value,
            yearBuilt: document.getElementById('yearBuilt').value,
            squareFootage: document.getElementById('squareFootage').value,
            averageRent: document.getElementById('averageRent').value,
            securityDeposit: document.getElementById('securityDeposit').value,
            propertyDescription: document.getElementById('propertyDescription').value,
            amenities: {
                parking: document.getElementById('parking').checked,
                pool: document.getElementById('pool').checked,
                gym: document.getElementById('gym').checked,
                laundry: document.getElementById('laundry').checked,
                elevator: document.getElementById('elevator').checked,
                balcony: document.getElementById('balcony').checked
            }
        };

        console.log('Property Data:', formData);
        
        // Show success message
        alert('Property added successfully!');
        
        // Close modal and reset form
        closeModalFunction();
    });

    // View Modal Functions
    const viewPropertyModal = document.getElementById('viewPropertyModal');
    const closeViewModal = document.getElementById('closeViewModal');

    function openViewModal(propertyId) {
        const property = propertyData[propertyId];
        if (!property) return;

        // Populate modal content
        document.getElementById('viewModalTitle').textContent = property.name;
        document.getElementById('viewPropertyName').textContent = property.name;
        document.getElementById('viewPropertyType').textContent = property.type;
        document.getElementById('viewPropertyAddress').textContent = property.address;
        document.getElementById('viewTotalUnits').textContent = property.totalUnits;
        document.getElementById('viewOccupiedUnits').textContent = property.occupiedUnits;
        document.getElementById('viewOccupancyRate').textContent = property.occupancyRate;
        document.getElementById('viewAverageRent').textContent = property.averageRent;
        document.getElementById('viewDescription').textContent = property.description;

        // Set status with appropriate styling
        const statusElement = document.getElementById('viewPropertyStatus');
        statusElement.textContent = property.status;
        statusElement.className = 'px-2 py-1 rounded-full text-xs font-medium';
        
        if (property.status === 'Active') {
            statusElement.classList.add('bg-green-100', 'text-green-800');
        } else if (property.status === 'Maintenance') {
            statusElement.classList.add('bg-yellow-100', 'text-yellow-800');
        } else if (property.status === 'Renovation') {
            statusElement.classList.add('bg-purple-100', 'text-purple-800');
        }

        // Populate images
        const imagesContainer = document.getElementById('propertyImages');
        imagesContainer.innerHTML = '';
        property.images.forEach((imageUrl, index) => {
            const imageDiv = document.createElement('div');
            imageDiv.className = 'relative group cursor-pointer';
            imageDiv.innerHTML = `
                <img src="${imageUrl}" alt="${property.name} - Image ${index + 1}" 
                     class="w-full h-48 object-cover rounded-lg shadow-sm group-hover:shadow-md transition-shadow">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center">
                    <i class="fas fa-expand text-white opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </div>
            `;
            imagesContainer.appendChild(imageDiv);
        });

        // Populate amenities
        const amenitiesContainer = document.getElementById('viewAmenities');
        amenitiesContainer.innerHTML = '';
        property.amenities.forEach(amenity => {
            const amenityDiv = document.createElement('div');
            amenityDiv.className = 'flex items-center space-x-2 bg-gray-50 px-3 py-2 rounded-lg';
            amenityDiv.innerHTML = `
                <i class="fas fa-check text-green-600 text-sm"></i>
                <span class="text-sm text-gray-700">${amenity}</span>
            `;
            amenitiesContainer.appendChild(amenityDiv);
        });

        // Show modal
        viewPropertyModal.classList.remove('hidden');
        viewPropertyModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeViewModalFunction() {
        viewPropertyModal.classList.add('hidden');
        viewPropertyModal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    closeViewModal.addEventListener('click', closeViewModalFunction);

    viewPropertyModal.addEventListener('click', (e) => {
        if (e.target === viewPropertyModal) {
            closeViewModalFunction();
        }
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !viewPropertyModal.classList.contains('hidden')) {
            closeViewModalFunction();
        }
    });
</script>
@endpush