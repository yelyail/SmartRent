<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartRent — Rental Management</title>

    {{-- TailwindCSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Vite compiled CSS -->
    @vite('resources/css/app.css')
    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-white text-gray-900">

    {{-- NAVBAR --}}
    <header class="border-b border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="/" class="text-xl font-semibold text-gray-900">
                    SmartRent
                </a>

                {{-- Desktop Menu --}}
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-700 hover:text-gray-900">Home</a>
                    <a href="/pricing" class="text-gray-700 hover:text-gray-900">Pricing</a>
                    <a href="/contact" class="text-gray-700 hover:text-gray-900">Contact</a>
                    <a href="/about" class="text-gray-700 hover:text-gray-900">About</a>
                </nav>

                {{-- Auth Buttons --}}
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/login" class="text-gray-700 hover:text-gray-900">Login</a>
                    <a href="/register"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Get Started
                    </a>
                </div>

                {{-- Mobile Button --}}
                <button id="mobileMenuBtn" class="md:hidden text-gray-700 text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobileMenu" class="md:hidden hidden border-t border-gray-200 bg-white">
            <nav class="px-6 py-4 space-y-4">
                <a href="/" class="block text-gray-700 hover:text-gray-900">Home</a>
                <a href="/pricing" class="block text-gray-700 hover:text-gray-900">Pricing</a>
                <a href="/contact" class="block text-gray-700 hover:text-gray-900">Contact</a>
                <a href="/about" class="block text-gray-700 hover:text-gray-900">About</a>

                <hr class="border-gray-200">

                <a href="/login" class="block text-gray-700 hover:text-gray-900">Login</a>
                <a href="/register"
                   class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                    Get Started
                </a>
            </nav>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-gray-200 bg-gray-50 py-8 mt-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-600 text-sm">
                    © {{ date('Y') }} SmartRent. All rights reserved.
                </p>

                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Mobile Menu Toggle Script --}}
    <script>
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>

</body>
</html>
