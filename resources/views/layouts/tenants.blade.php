<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartRent - Property Management Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Vite compiled CSS -->
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg flex flex-col">
            <!-- Logo Section -->
            <div class="p-2 border-b border-gray-700">
                <div class="flex items-center space-x-4">
                    <div class="w-24 h-24 rounded-xl flex items-center justify-center shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="SmartRent Logo" class="w-24 h-24 object-contain">
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-gray-900">SmartRent</h2>
                        <p class="text-xs text-gray-400 font-medium">Management System</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('tenants.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors {{ request()->routeIs('tenants.dashboard') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                            <i class="fas fa-th-large w-5"></i>
                            <span class="{{ request()->routeIs('tenants.dashboard') ? 'font-medium' : '' }}">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tenants.leases') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors {{ request()->routeIs('tenants.leases*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                            <i class="fas fa-file-contract w-5"></i>
                            <span class="{{ request()->routeIs('tenants.leases*') ? 'font-medium' : '' }}">My Rentals</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tenants.properties') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors {{ request()->routeIs('tenants.properties*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                            <i class="fas fa-building w-5"></i>
                            <span class="{{ request()->routeIs('tenants.properties*') ? 'font-medium' : '' }}">Unit Details</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tenants.propAssets') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors {{ request()->routeIs('tenants.propAssets*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                            <i class="fas fa-mobile-alt w-5"></i>
                            <span class="{{ request()->routeIs('tenants.propAssets*') ? 'font-medium' : '' }}">Smart Controls</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tenants.maintenance') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors {{ request()->routeIs('tenants.maintenance*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                            <i class="fas fa-wrench w-5"></i>
                            <span class="{{ request()->routeIs('tenants.maintenance*') ? 'font-medium' : '' }}">Service Requests</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('tenants.analytics') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors {{ request()->routeIs('tenants.analytics*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                            <i class="fas fa-chart-bar w-5"></i>
                            <span class="{{ request()->routeIs('tenants.analytics*') ? 'font-medium' : '' }}">Reports</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Profile -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-gray-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-md font-semibold text-gray-900 truncate capitalize">
                            @auth
                                {{ Auth::user()->first_name }} 
                                @if(Auth::user()->middle_name)
                                    {{ substr(Auth::user()->middle_name, 0, 1) }}.
                                @endif
                                {{ Auth::user()->last_name }}
                            @else
                                John Manager
                            @endauth
                        </p>
                        <p class="text-xs text-gray-400 truncate capitalize">{{ Auth::user()->role }}</p>
                    </div>
                    <i class="fas fa-cog text-gray-400 ml-auto cursor-pointer hover:text-gray-600 transition-colors"></i>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Header -->
            <div class="bg-white border-b border-gray-200 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-gray-600 mt-1">@yield('page-description', 'Welcome back! Here\'s what\'s happening with your properties.')</p>
                    </div>
                    <div class="flex space-x-3">
                        @yield('header-actions')
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="p-8">
                @yield('content')
            </div>
        </div>
    </div>
    @stack('modals')
    @stack('scripts')
</body>
</html>