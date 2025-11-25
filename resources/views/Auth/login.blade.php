<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartRent - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Vite compiled CSS -->
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.min.css" rel="stylesheet">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class=" font-sans bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 transform transition-all duration-300 hover:shadow-2xl">
            <div class="flex items-center justify-between mb-8">
                <!-- Logo on the left -->
                <div class="flex items-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-xl shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="SmartRent Logo" class="w-24 h-24 rounded-xl">
                    </div>
                </div>
                
                <!-- Welcome text on the right -->
                <div class="text-right">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                    <p class="text-gray-600">Sign in to access your credentials</p>
                </div>
            </div>
            <form class="space-y-6" method="POST" action="{{ route('login.store') }}" >
                @csrf
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            placeholder="juan@gmail.com"
                        >
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            placeholder="*********"
                        >
                        <button 
                            type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            onclick="togglePassword()"
                        >
                            <i id="password-toggle" class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember-me" 
                            name="remember-me" 
                            type="checkbox" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-500 font-medium transition-colors">
                        Forgot password?
                    </a>
                </div>

                <!-- Sign In Button -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-3 px-4 rounded-xl font-medium hover:from-blue-700 hover:to-indigo-800 focus:ring-4 focus:ring-blue-200 transition-all duration-200 flex items-center justify-center space-x-2 shadow-md hover:shadow-lg"
                >
                    <span>Sign In</span>
                    <i class="fas fa-arrow-right text-sm"></i>
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    Don't have an account?  
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium transition-colors ml-1">
                        Sign up
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-xs text-gray-500">
                &copy; 2023 SmartRent. All rights reserved.
            </p>
        </div>
    </div>
    <!-- SweetAlert2 Messages -->
    @if(session('success'))
        <div data-success="{{ session('success') }}"></div>
    @endif

    @if(session('error'))
        <div data-error="{{ session('error') }}"></div>
    @endif

    @if($errors->any())
        <div data-error="{{ implode(' ', $errors->all()) }}"></div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check for success message
            const successMessage = document.querySelector('[data-success]');
            if (successMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: successMessage.getAttribute('data-success'),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3B82F6',
                });
            }

            // Check for error message
            const errorMessage = document.querySelector('[data-error]');
            if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: errorMessage.getAttribute('data-error'),
                    confirmButtonText: 'Try Again',
                    confirmButtonColor: '#EF4444',
                });
            }

            // Check for CSRF token issues
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const csrfToken = form.querySelector('input[name="_token"]');
                    if (!csrfToken || !csrfToken.value) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Page Expired',
                            text: 'Please refresh the page and try again.',
                            confirmButtonText: 'Refresh',
                            confirmButtonColor: '#EF4444',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    }
                });
            });
        });
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form submission handler
        document.querySelector('form').addEventListener('submit', function(e) {            
            // Simple validation
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (email && password) {
                // Simulate login process
                const button = document.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing in...';
                button.disabled = true;
                
                setTimeout(() => {
                    // Reset button after 3 seconds
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 1000);
                }, 2000);
            }
        });
    </script>
</body>
</html>