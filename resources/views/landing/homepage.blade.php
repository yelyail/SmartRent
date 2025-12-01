@extends('layouts.landing')

@section('title', 'SmartRent — Available Rental Properties')
@section('description', 'Browse our selection of premium rental properties. Find your perfect home with SmartRent.')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-primary-500 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 left-0 w-1/3 h-1/3 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
    </div>
    
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
            Find Your Perfect Home
        </h1>
        <p class="text-xl text-primary-100 max-w-3xl mx-auto mb-8">
            Browse our curated selection of premium rental properties. From modern apartments to spacious family homes.
        </p>
        
        <!-- Stats Banner -->
        <div class="inline-flex flex-wrap justify-center gap-6 md:gap-10 mb-8 p-6 bg-white/10 backdrop-blur-sm rounded-2xl">
            <div class="text-center">
                <div class="text-2xl font-bold text-white">250+</div>
                <div class="text-primary-200 text-sm">Properties Available</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white">98%</div>
                <div class="text-primary-200 text-sm">Occupancy Rate</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white">24/7</div>
                <div class="text-primary-200 text-sm">Virtual Tours</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white">4.8★</div>
                <div class="text-primary-200 text-sm">Tenant Rating</div>
            </div>
        </div>
    </div>
</section>

<!-- Search & Filters -->
<section class="py-8 bg-white border-b border-gray-100 sticky top-16 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Search Bar -->
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           placeholder="Search properties by location, name, or amenities..." 
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                           id="propertySearch">
                </div>
            </div>
            
            <!-- Filters -->
            <div class="flex gap-3">
                <select class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option>Property Type</option>
                    <option>Apartment</option>
                    <option>House</option>
                    <option>Condo</option>
                    <option>Townhouse</option>
                </select>
                
                <select class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option>Price Range</option>
                    <option>₱10,000 - ₱20,000</option>
                    <option>₱20,000 - ₱35,000</option>
                    <option>₱35,000 - ₱50,000</option>
                    <option>₱50,000+</option>
                </select>
                
                <select class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option>Bedrooms</option>
                    <option>Studio</option>
                    <option>1 Bedroom</option>
                    <option>2 Bedrooms</option>
                    <option>3+ Bedrooms</option>
                </select>
                
                <button class="px-6 py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Properties Grid -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Featured Properties</h2>
                <p class="text-gray-600 mt-2">Hand-picked properties with premium amenities</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="inline-flex items-center space-x-2 bg-white border border-gray-200 rounded-xl p-2">
                    <button class="px-4 py-2 rounded-lg bg-primary-50 text-primary-600 font-medium">
                        <i class="fas fa-th-large mr-2"></i>Grid
                    </button>
                    <button class="px-4 py-2 rounded-lg text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="fas fa-list mr-2"></i>List
                    </button>
                </div>
            </div>
        </div>

        <!-- Properties Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="propertiesGrid">
            <!-- Property Card 1 -->
            <div class="property-card bg-white rounded-2xl overflow-hidden border border-gray-200 hover:border-primary-200 transition-all duration-300 hover:shadow-xl group">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1613490493576-7fde63acd811?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Modern Apartment in BGC" 
                         class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 bg-primary-600 text-white text-sm font-medium rounded-full">
                            Featured
                        </span>
                    </div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-900 text-sm font-medium rounded-full">
                            ₱35,000/month
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">Sky Garden Residences</h3>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt text-sm mr-2"></i>
                                <span>Bonifacio Global City, Taguig</span>
                            </div>
                        </div>
                        <div class="flex items-center text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt ml-1"></i>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mb-6">
                        Modern 2-bedroom apartment with panoramic city views, premium finishes, and access to exclusive amenities.
                    </p>
                    
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">2</div>
                            <div class="text-gray-500 text-sm">Bedrooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">2</div>
                            <div class="text-gray-500 text-sm">Bathrooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">85</div>
                            <div class="text-gray-500 text-sm">Sq. M.</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                Available Now
                            </span>
                        </div>
                        <button class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors inline-flex items-center">
                            <i class="fas fa-eye mr-2"></i>
                            View Details
                        </button>
                    </div>
                </div>
            </div>

            <!-- Property Card 2 -->
            <div class="property-card bg-white rounded-2xl overflow-hidden border border-gray-200 hover:border-primary-200 transition-all duration-300 hover:shadow-xl group">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Luxury Condo in Makati" 
                         class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 bg-green-600 text-white text-sm font-medium rounded-full">
                            New Listing
                        </span>
                    </div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-900 text-sm font-medium rounded-full">
                            ₱45,000/month
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">Makati Central Tower</h3>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt text-sm mr-2"></i>
                                <span>Makati Central Business District</span>
                            </div>
                        </div>
                        <div class="flex items-center text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star ml-1"></i>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mb-6">
                        Luxury 3-bedroom condo with smart home features, floor-to-ceiling windows, and premium appliances.
                    </p>
                    
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">3</div>
                            <div class="text-gray-500 text-sm">Bedrooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">3</div>
                            <div class="text-gray-500 text-sm">Bathrooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">120</div>
                            <div class="text-gray-500 text-sm">Sq. M.</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-clock text-orange-500 mr-1"></i>
                                Available Dec 1
                            </span>
                        </div>
                        <button class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors inline-flex items-center">
                            <i class="fas fa-calendar mr-2"></i>
                            Schedule Tour
                        </button>
                    </div>
                </div>
            </div>

            <!-- Property Card 3 -->
            <div class="property-card bg-white rounded-2xl overflow-hidden border border-gray-200 hover:border-primary-200 transition-all duration-300 hover:shadow-xl group">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Townhouse in Quezon City" 
                         class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-full">
                            Pet Friendly
                        </span>
                    </div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-900 text-sm font-medium rounded-full">
                            ₱28,000/month
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">Greenfield Townhomes</h3>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt text-sm mr-2"></i>
                                <span>Quezon City, Metro Manila</span>
                            </div>
                        </div>
                        <div class="flex items-center text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star ml-1"></i>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mb-6">
                        Spacious 3-bedroom townhouse with private garden, 2-car garage, and family-friendly neighborhood.
                    </p>
                    
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">3</div>
                            <div class="text-gray-500 text-sm">Bedrooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">2.5</div>
                            <div class="text-gray-500 text-sm">Bathrooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">150</div>
                            <div class="text-gray-500 text-sm">Sq. M.</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                Available Now
                            </span>
                        </div>
                        <button class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors inline-flex items-center">
                            <i class="fas fa-eye mr-2"></i>
                            View Details
                        </button>
                    </div>
                </div>
            </div>

            <!-- Property Card 4 -->
            <div class="property-card bg-white rounded-2xl overflow-hidden border border-gray-200 hover:border-primary-200 transition-all duration-300 hover:shadow-xl group">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Studio Apartment in Ortigas" 
                         class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 bg-purple-600 text-white text-sm font-medium rounded-full">
                            Furnished
                        </span>
                    </div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-900 text-sm font-medium rounded-full">
                            ₱18,000/month
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">Ortigas Central Studio</h3>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt text-sm mr-2"></i>
                                <span>Ortigas Center, Pasig</span>
                            </div>
                        </div>
                        <div class="flex items-center text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt ml-1"></i>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mb-6">
                        Fully-furnished studio apartment perfect for professionals, includes utilities and high-speed internet.
                    </p>
                    
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">Studio</div>
                            <div class="text-gray-500 text-sm">Layout</div>
                        </div>
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">1</div>
                            <div class="text-gray-500 text-sm">Bathroom</div>
                        </div>
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">35</div>
                            <div class="text-gray-500 text-sm">Sq. M.</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                Available Now
                            </span>
                        </div>
                        <button class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors inline-flex items-center">
                            <i class="fas fa-calendar mr-2"></i>
                            Schedule Tour
                        </button>
                    </div>
                </div>
            </div>

            <!-- Property Card 5 -->
            <div class="property-card bg-white rounded-2xl overflow-hidden border border-gray-200 hover:border-primary-200 transition-all duration-300 hover:shadow-xl group">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1568605114967-8130f3a36994?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Family House in Alabang" 
                         class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 bg-orange-600 text-white text-sm font-medium rounded-full">
                            Family Home
                        </span>
                    </div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-900 text-sm font-medium rounded-full">
                            ₱55,000/month
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">Alabang Family Residence</h3>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt text-sm mr-2"></i>
                                <span>Alabang, Muntinlupa</span>
                            </div>
                        </div>
                        <div class="flex items-center text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star ml-1"></i>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mb-6">
                        4-bedroom family house with large backyard, swimming pool, and gated community security.
                    </p>
                    
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">4</div>
                            <div class="text-gray-500 text-sm">Bedrooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">3</div>
                            <div class="text-gray-500 text-sm">Bathrooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">220</div>
                            <div class="text-gray-500 text-sm">Sq. M.</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-clock text-orange-500 mr-1"></i>
                                Available Jan 15
                            </span>
                        </div>
                        <button class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors inline-flex items-center">
                            <i class="fas fa-eye mr-2"></i>
                            View Details
                        </button>
                    </div>
                </div>
            </div>

            <!-- Property Card 6 -->
            <div class="property-card bg-white rounded-2xl overflow-hidden border border-gray-200 hover:border-primary-200 transition-all duration-300 hover:shadow-xl group">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Penthouse in Rockwell" 
                         class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 bg-red-600 text-white text-sm font-medium rounded-full">
                            Luxury
                        </span>
                    </div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-900 text-sm font-medium rounded-full">
                            ₱85,000/month
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">Rockwell Penthouse Suite</h3>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt text-sm mr-2"></i>
                                <span>Rockwell Center, Makati</span>
                            </div>
                        </div>
                        <div class="flex items-center text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star ml-1"></i>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mb-6">
                        Exclusive penthouse with private elevator, panoramic views, premium finishes, and 24/7 concierge service.
                    </p>
                    
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">3</div>
                            <div class="text-gray-500 text-sm">Bedrooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">3.5</div>
                            <div class="text-gray-500 text-sm">Bathrooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-gray-900 font-bold text-lg">180</div>
                            <div class="text-gray-500 text-sm">Sq. M.</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-clock text-orange-500 mr-1"></i>
                                Available Feb 1
                            </span>
                        </div>
                        <button class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors inline-flex items-center">
                            <i class="fas fa-calendar mr-2"></i>
                            Schedule Tour
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-12">
            <button class="px-8 py-3.5 border-2 border-primary-600 text-primary-600 font-medium rounded-xl hover:bg-primary-50 transition-colors inline-flex items-center">
                <i class="fas fa-sync-alt mr-2"></i>
                Load More Properties
            </button>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary-600 to-primary-700">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-block p-2 bg-white/10 rounded-2xl backdrop-blur-sm mb-8">
            <span class="text-white font-medium px-4 py-2">Ready to move in?</span>
        </div>
        <h2 class="text-3xl font-bold text-white mb-6">
            Can't find what you're looking for?
        </h2>
        <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
            Our property experts can help you find the perfect rental that matches your needs and preferences.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/contact" 
               class="px-8 py-4 bg-white text-primary-700 font-semibold rounded-xl hover:bg-primary-50 transition-all duration-300 hover:-translate-y-1 shadow-lg hover:shadow-xl inline-flex items-center justify-center">
                <i class="fas fa-headset mr-2"></i>
                <span>Contact Our Agents</span>
            </a>
            <a href="/register" 
               class="px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-all duration-300 inline-flex items-center justify-center">
                <i class="fas fa-bell mr-2"></i>
                <span>Get Alerts for New Listings</span>
            </a>
        </div>
    </div>
</section>
<!-- Features Section -->
<section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Everything You Need to Manage Rentals
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                From property listing to tenant communication, we've got you covered
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="group bg-gradient-to-br from-white to-blue-50 rounded-2xl p-8 border border-gray-100 hover:border-blue-200 transition-all duration-300 hover:shadow-xl">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-home text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Property Management</h3>
                <p class="text-gray-600 mb-4">
                    Upload properties, manage units, track leases, and automate tenant screening.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Unlimited property listings
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Automated lease generation
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Tenant background checks
                    </li>
                </ul>
            </div>

            <!-- Feature 2 -->
            <div class="group bg-gradient-to-br from-white to-green-50 rounded-2xl p-8 border border-gray-100 hover:border-green-200 transition-all duration-300 hover:shadow-xl">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-file-invoice-dollar text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Automated Payments</h3>
                <p class="text-gray-600 mb-4">
                    Generate invoices, track payments, and avoid missed rent with automated reminders.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Multiple payment gateways
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Auto-generated invoices
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Late payment tracking
                    </li>
                </ul>
            </div>

            <!-- Feature 3 -->
            <div class="group bg-gradient-to-br from-white to-orange-50 rounded-2xl p-8 border border-gray-100 hover:border-orange-200 transition-all duration-300 hover:shadow-xl">
                <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-tools text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Maintenance Management</h3>
                <p class="text-gray-600 mb-4">
                    Submit, assign, and track maintenance requests in real-time with photo support.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Real-time request tracking
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Photo upload support
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Priority-based assignments
                    </li>
                </ul>
            </div>

            <!-- Feature 4 -->
            <div class="group bg-gradient-to-br from-white to-purple-50 rounded-2xl p-8 border border-gray-100 hover:border-purple-200 transition-all duration-300 hover:shadow-xl">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-chart-line text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Advanced Analytics</h3>
                <p class="text-gray-600 mb-4">
                    Get insights into your property performance, occupancy rates, and revenue trends.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Real-time dashboards
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Revenue forecasting
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Occupancy analytics
                    </li>
                </ul>
            </div>

            <!-- Feature 5 -->
            <div class="group bg-gradient-to-br from-white to-pink-50 rounded-2xl p-8 border border-gray-100 hover:border-pink-200 transition-all duration-300 hover:shadow-xl">
                <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-mobile-alt text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Mobile App</h3>
                <p class="text-gray-600 mb-4">
                    Manage your properties on the go with our iOS and Android mobile applications.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Push notifications
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Offline access
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Biometric login
                    </li>
                </ul>
            </div>

            <!-- Feature 6 -->
            <div class="group bg-gradient-to-br from-white to-cyan-50 rounded-2xl p-8 border border-gray-100 hover:border-cyan-200 transition-all duration-300 hover:shadow-xl">
                <div class="w-16 h-16 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-headset text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">24/7 Support</h3>
                <p class="text-gray-600 mb-4">
                    Get help when you need it with our round-the-clock customer support team.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Live chat support
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Phone & email support
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Dedicated account manager
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- FAQ Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Renting FAQ
            </h2>
            <p class="text-lg text-gray-600">
                Common questions about renting with SmartRent
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- FAQ Item 1 -->
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    What documents do I need to rent a property?
                </h3>
                <p class="text-gray-600">
                    Typically, you'll need valid ID, proof of income, and references. Specific requirements may vary by property.
                </p>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    How long is the typical lease term?
                </h3>
                <p class="text-gray-600">
                    Most properties offer 12-month leases, but we also have options for 6-month and 24-month terms.
                </p>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    Are utilities included in the rent?
                </h3>
                <p class="text-gray-600">
                    It depends on the property. Some include basic utilities, while others require tenants to set up their own accounts.
                </p>
            </div>

            <!-- FAQ Item 4 -->
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    Can I schedule a virtual tour?
                </h3>
                <p class="text-gray-600">
                    Yes! All our properties offer 3D virtual tours. You can also schedule live video tours with our agents.
                </p>
            </div>

            <!-- FAQ Item 5 -->
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    What's the security deposit amount?
                </h3>
                <p class="text-gray-600">
                    Security deposits are typically 1-2 months' rent, depending on the property and your rental history.
                </p>
            </div>

            <!-- FAQ Item 6 -->
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    How quickly can I move in?
                </h3>
                <p class="text-gray-600">
                    Once approved, you can usually move in within 3-7 days, depending on property availability and paperwork.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Property Search Functionality
    const propertySearch = document.getElementById('propertySearch');
    const propertyCards = document.querySelectorAll('.property-card');
    
    if (propertySearch) {
        propertySearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            
            propertyCards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const location = card.querySelector('.fa-map-marker-alt + span').textContent.toLowerCase();
                const description = card.querySelector('p.text-gray-600').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || location.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Load More Properties Simulation
    document.querySelector('button:contains("Load More Properties")').addEventListener('click', function() {
        const button = this;
        const originalText = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
        button.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            // In a real application, you would fetch more properties here
            alert('More properties loaded! In a real application, this would fetch and display additional property listings.');
            
            // Reset button
            button.innerHTML = originalText;
            button.disabled = false;
        }, 1500);
    });

    // View Details/Schedule Tour Buttons
    document.querySelectorAll('.property-card button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const propertyTitle = this.closest('.property-card').querySelector('h3').textContent;
            
            if (this.textContent.includes('View Details')) {
                alert(`Viewing details for: ${propertyTitle}\n\nThis would open a detailed property page with photos, floor plans, amenities, and application process.`);
            } else if (this.textContent.includes('Schedule Tour')) {
                alert(`Scheduling tour for: ${propertyTitle}\n\nThis would open a calendar to schedule an in-person or virtual tour with our property agents.`);
            }
        });
    });

    // Property Card Click (for entire card)
    document.querySelectorAll('.property-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Only trigger if click is not on a button or interactive element
            if (!e.target.closest('button') && !e.target.closest('a')) {
                const propertyTitle = this.querySelector('h3').textContent;
                alert(`Selected property: ${propertyTitle}\n\nRedirecting to property details page...`);
                // In production, you would redirect to property detail page
                // window.location.href = `/properties/${propertyId}`;
            }
        });
    });

    // Filter functionality
    document.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', function() {
            // In production, this would filter properties based on selected criteria
            console.log(`Filter changed: ${this.previousElementSibling?.textContent || 'Unknown'} = ${this.value}`);
        });
    });

    // Grid/List View Toggle
    document.querySelectorAll('.inline-flex button').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active state from all buttons
            this.parentElement.querySelectorAll('button').forEach(btn => {
                btn.classList.remove('bg-primary-50', 'text-primary-600');
                btn.classList.add('text-gray-600', 'hover:text-gray-900');
            });
            
            // Add active state to clicked button
            this.classList.remove('text-gray-600', 'hover:text-gray-900');
            this.classList.add('bg-primary-50', 'text-primary-600');
            
            // Toggle between grid and list view
            const grid = document.getElementById('propertiesGrid');
            if (this.textContent.includes('List')) {
                grid.classList.remove('lg:grid-cols-3');
                grid.classList.add('lg:grid-cols-1');
            } else {
                grid.classList.remove('lg:grid-cols-1');
                grid.classList.add('lg:grid-cols-3');
            }
        });
    });
</script>
@endpush