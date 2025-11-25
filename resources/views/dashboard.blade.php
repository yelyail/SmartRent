<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Dashboard - SmartRent</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl shadow-lg bg-white">
                    <img src="{{ asset('images/logo.png') }}" alt="SmartRent Logo" class="w-16 h-16 rounded-xl">
                </div>
            </div>

            <!-- Guest Message Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8 text-center">
                <!-- Warning Icon -->
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                </div>
                
                <!-- Title -->
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Guest Mode Active</h1>
                
                <!-- Message -->
                <p class="text-gray-600 mb-6 leading-relaxed">
                    You are currently in guest mode with limited access. 
                    Please contact your administrator to upgrade your account 
                    and access full system features.
                </p>
                
                <!-- Contact Information -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-center space-x-2 text-blue-700">
                        <i class="fas fa-envelope"></i>
                        <span class="font-medium">admin@smartrent.com</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2 text-blue-700 mt-2">
                        <i class="fas fa-phone"></i>
                        <span class="font-medium">+1 (555) 123-4567</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <!-- Contact Admin Button -->
                    <button 
                        onclick="window.location.href='mailto:admin@smartrent.com'"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-xl font-medium transition-all duration-200 flex items-center justify-center space-x-2"
                    >
                        <i class="fas fa-envelope"></i>
                        <span>Contact Administrator</span>
                    </button>
                    
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button 
                            type="submit"
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-4 rounded-xl font-medium transition-all duration-200 flex items-center justify-center space-x-2"
                        >
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-500">
                    Need immediate assistance? 
                    <a href="tel:+09552677446" class="text-blue-600 hover:text-blue-500 font-medium">
                        Call Support
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>