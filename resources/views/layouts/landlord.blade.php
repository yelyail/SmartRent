<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('', 'SmartRent - Property Management Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Vite compiled CSS -->
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64  shadow-xl flex flex-col">
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
        <nav class="flex-1 px-4 py-6 overflow-y-auto">
            <div class="space-y-1">

                <div class="px-3 mb-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Main</p>
                </div>

                <ul class="space-y-1">

                    {{-- Dashboard --}}
                    <li>
                        <a href="{{ route('landlords.dashboard') }}"
                           class="flex items-center space-x-3 px-4 py-3 text-gray-400 rounded-xl 
                           transition-all duration-200 group
                           hover:bg-blue-500 hover:text-white
                           {{ request()->routeIs('landlords.dashboard') ? 'bg-blue-600 text-white shadow-lg' : '' }}">
                           
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-th-large text-sm 
                                   {{ request()->routeIs('landlords.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}">
                                </i>
                            </div>
                            <span class="font-medium">Dashboard</span>

                            @if(request()->routeIs('landlords.dashboard'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>
                    </li>

                    {{-- Properties --}}
                    <li>
                        <a href="{{ route('landlords.properties') }}"
                           class="flex items-center space-x-3 px-4 py-3 text-gray-400 rounded-xl 
                           transition-all duration-200 group
                           hover:bg-blue-500 hover:text-white
                           {{ request()->routeIs('landlords.properties*') ? 'bg-blue-600 text-white shadow-lg' : '' }}">

                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-building text-sm 
                                   {{ request()->routeIs('landlords.properties*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}">
                                </i>
                            </div>
                            <span class="font-medium">My Properties</span>

                            @if(request()->routeIs('landlords.properties*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>
                    </li>

                    {{-- Smart Devices --}}
                    <li>
                        <a href="{{ route('landlords.propAssets') }}"
                           class="flex items-center space-x-3 px-4 py-3 text-gray-400 rounded-xl 
                           transition-all duration-200 group
                           hover:bg-blue-500 hover:text-white
                           {{ request()->routeIs('landlords.propAssets*') ? 'bg-blue-600 text-white shadow-lg' : '' }}">

                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-mobile-alt text-sm 
                                   {{ request()->routeIs('landlords.propAssets*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}">
                                </i>
                            </div>
                            <span class="font-medium">Smart Devices</span>

                            @if(request()->routeIs('landlords.propAssets*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>
                    </li>

                    {{-- Tenants --}}
                    <li>
                        <a href="{{ route('landlords.userManagement') }}"
                           class="flex items-center space-x-3 px-4 py-3 text-gray-400 rounded-xl 
                           transition-all duration-200 group
                           hover:bg-blue-500 hover:text-white
                           {{ request()->routeIs('landlords.userManagement*') ? 'bg-blue-600 text-white shadow-lg' : '' }}">

                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-users text-sm 
                                   {{ request()->routeIs('landlords.userManagement*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}">
                                </i>
                            </div>
                            <span class="font-medium">Renters</span>

                            @if(request()->routeIs('landlords.userManagement*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>
                    </li>

                    {{-- Maintenance --}}
                    <li>
                        <a href="{{ route('landlords.maintenance') }}"
                           class="flex items-center space-x-3 px-4 py-3 text-gray-400 rounded-xl 
                           transition-all duration-200 group
                           hover:bg-blue-500 hover:text-white
                           {{ request()->routeIs('landlords.maintenance*') ? 'bg-blue-600 text-white shadow-lg' : '' }}">
                            
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-wrench text-sm
                                   {{ request()->routeIs('landlords.maintenance*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}">
                                </i>
                            </div>
                            <span class="font-medium">Maintenance Requests</span>

                            @if(request()->routeIs('landlords.maintenance*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>
                    </li>

                    {{-- Analytics --}}
                    <li>
                        <a href="{{ route('landlords.analytics') }}"
                           class="flex items-center space-x-3 px-4 py-3 text-gray-400 rounded-xl 
                           transition-all duration-200 group
                           hover:bg-blue-500 hover:text-white
                           {{ request()->routeIs('landlords.analytics*') ? 'bg-blue-600 text-white shadow-lg' : '' }}">

                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-chart-bar text-sm
                                   {{ request()->routeIs('landlords.analytics*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}">
                                </i>
                            </div>
                            <span class="font-medium">Analytics & Reports</span>

                            @if(request()->routeIs('landlords.analytics*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>
                    </li>

                </ul>
            </div>
        </nav>

            <!-- Profile Section -->
            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 border-2 border-gray-800 rounded-full"></div>
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
                    <div class="relative group">
                         <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-3 text-xs"></i>
                                </button>
                            </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 shadow-sm">
                <div class="px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button class="lg:hidden w-8 h-8 flex items-center justify-center text-gray-500 hover:text-gray-700 transition-colors duration-200 rounded-lg hover:bg-gray-100">
                                <i class="fas fa-bars text-lg"></i>
                            </button>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                                <p class="text-gray-600 mt-1">@yield('page-description', 'Welcome back! Here\'s what\'s happening with your properties.')</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">

                            @yield('header-actions')
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto bg-gray-50/50">
                <div class="p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @stack('modals')
    @stack('scripts')
</body>
</html>