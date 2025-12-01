<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield( '','SmartRent - Property Management Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Vite compiled CSS -->
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-home text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">SmartRent</h2>
                        <p class="text-sm text-gray-500">Management System</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('staff.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors {{ request()->routeIs('staff.dashboard') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                            <i class="fas fa-th-large w-5"></i>
                            <span class="{{ request()->routeIs('staff.dashboard') ? 'font-medium' : '' }}">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('staff.maintenance') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors {{ request()->routeIs('staff.maintenance*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                            <i class="fas fa-wrench w-5"></i>
                            <span class="{{ request()->routeIs('staff.maintenance*') ? 'font-medium' : '' }}">Maintenance</span>
                        </a>
                    </li>
                </ul>
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