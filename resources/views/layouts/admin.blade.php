<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartRent - Property Management Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Vite compiled CSS -->
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64  shadow-xl flex flex-col">
            <!-- Logo Section -->
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="SmartRent Logo" class="w-8 h-8 object-contain">
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-white">SmartRent</h2>
                        <p class="text-xs text-gray-400 font-medium">Management System</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6">
                <div class="space-y-1">
                    <div class="px-3 mb-4">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Main Menu</p>
                    </div>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('admins.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl transition-all duration-200 group {{ request()->routeIs('admins.dashboard') ? 'bg-blue-600 text-white shadow-lg' : '' }}">
                                <div class="w-6 h-6 flex items-center justify-center">
                                    <i class="fas fa-th-large text-sm {{ request()->routeIs('admins.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                                </div>
                                <span class="font-medium">Dashboard</span>
                                @if(request()->routeIs('admins.dashboard'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admins.properties') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl transition-all duration-200 group {{ request()->routeIs('admins.properties*') ? 'bg-blue-600 text-white shadow-lg' : '' }}">
                                <div class="w-6 h-6 flex items-center justify-center">
                                    <i class="fas fa-building text-sm {{ request()->routeIs('admins.properties*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                                </div>
                                <span class="font-medium">Properties</span>
                                @if(request()->routeIs('admins.properties*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admins.propAssets') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl transition-all duration-200 group {{ request()->routeIs('admins.propAssets*') ? 'bg-blue-600 text-white shadow-lg' : '' }}">
                                <div class="w-6 h-6 flex items-center justify-center">
                                    <i class="fas fa-mobile-alt text-sm {{ request()->routeIs('admins.propAssets*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                                </div>
                                <span class="font-medium">Smart Devices</span>
                                @if(request()->routeIs('admins.propAssets*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admins.userManagement') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl transition-all duration-200 group {{ request()->routeIs('admins.userManagement*') ? 'bg-blue-600 text-white shadow-lg' : '' }}">
                                <div class="w-6 h-6 flex items-center justify-center">
                                    <i class="fas fa-users text-sm {{ request()->routeIs('admins.userManagement*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                                </div>
                                <span class="font-medium">User Management</span>
                                @if(request()->routeIs('admins.userManagement*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admins.maintenance') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl transition-all duration-200 group {{ request()->routeIs('admins.maintenance*') ? 'bg-blue-600 text-white shadow-lg' : '' }}">
                                <div class="w-6 h-6 flex items-center justify-center">
                                    <i class="fas fa-wrench text-sm {{ request()->routeIs('admins.maintenance*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                                </div>
                                <span class="font-medium">Maintenance</span>
                                @if(request()->routeIs('admins.maintenance*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admins.analytics') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl transition-all duration-200 group {{ request()->routeIs('admins.analytics*') ? 'bg-blue-600 text-white shadow-lg' : '' }}">
                                <div class="w-6 h-6 flex items-center justify-center">
                                    <i class="fas fa-chart-bar text-sm {{ request()->routeIs('admins.analytics*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                                </div>
                                <span class="font-medium">Analytics</span>
                                @if(request()->routeIs('admins.analytics*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Support Section -->
                <div class="mt-8 px-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Support</p>
                    <ul class="space-y-1">
                        <li>
                            <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl transition-all duration-200 group">
                                <div class="w-6 h-6 flex items-center justify-center">
                                    <i class="fas fa-question-circle text-sm text-gray-400 group-hover:text-white"></i>
                                </div>
                                <span class="font-medium">Help & Support</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl transition-all duration-200 group">
                                <div class="w-6 h-6 flex items-center justify-center">
                                    <i class="fas fa-cog text-sm text-gray-400 group-hover:text-white"></i>
                                </div>
                                <span class="font-medium">Settings</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Profile Section -->
            <div class="p-4 border-t border-gray-700 bg-gray-800/50">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 border-2 border-gray-800 rounded-full"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'John Manager' }}</p>
                        <p class="text-xs text-gray-400 truncate">Property Manager</p>
                    </div>
                    <div class="relative group">
                        <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white transition-colors duration-200 rounded-lg hover:bg-gray-700">
                            <i class="fas fa-ellipsis-v text-sm"></i>
                        </button>
                        <div class="absolute bottom-full left-0 mb-2 w-48 bg-gray-700 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                            <div class="py-1">
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white">
                                    <i class="fas fa-user-edit mr-3 text-xs"></i>
                                    Edit Profile
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white">
                                    <i class="fas fa-cog mr-3 text-xs"></i>
                                    Settings
                                </a>
                                <div class="border-t border-gray-600 my-1"></div>
                                <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 text-sm text-red-400 hover:bg-gray-600 hover:text-red-300">
                                    <i class="fas fa-sign-out-alt mr-3 text-xs"></i>
                                    Logout
                                </a>
                            </div>
                        </div>
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
                            <!-- Notifications -->
                            <div class="relative group">
                                <button class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-gray-700 transition-colors duration-200 rounded-lg hover:bg-gray-100 relative">
                                    <i class="fas fa-bell text-lg"></i>
                                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                                </button>
                                <div class="absolute top-full right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                                    <div class="p-4 border-b border-gray-200">
                                        <h3 class="font-semibold text-gray-900">Notifications</h3>
                                    </div>
                                    <div class="max-h-60 overflow-y-auto">
                                        <!-- Notification items would go here -->
                                    </div>
                                </div>
                            </div>

                            <!-- Search -->
                            <div class="relative">
                                <input type="text" placeholder="Search..." class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>

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