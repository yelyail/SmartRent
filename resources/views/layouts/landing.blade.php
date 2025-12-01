<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartRent — Rental Management</title>

    {{-- TailwindCSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Custom Tailwind Config --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {50: '#eff6ff',100: '#dbeafe',200: '#bfdbfe',300: '#93c5fd',400: '#60a5fa',500: '#3b82f6',600: '#2563eb',700: '#1d4ed8',800: '#1e40af',900: '#1e3a8a',}
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="bg-white text-gray-900 font-sans antialiased">
    {{-- NAVBAR --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-sm border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                {{-- Logo --}}
                <a href="/" class="flex items-center space-x-2 group">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-600 to-primary-800 rounded-lg flex items-center justify-center">
                        <image accesskey="logo" src="/images/logo.png" class="w-20 h-20"/>
                    </div>
                    <span class="text-xl font-bold text-gray-900 tracking-tight"></span>
                </a>

                {{-- Desktop Menu --}}
                <nav class="hidden lg:flex items-center space-x-8">
                    <a href="/" class="text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200">Home</a>
                    <a href="/pricing" class="text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200">Pricing</a>
                    <a href="/about" class="text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200">About</a>
                    <a href="/contact" class="text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200">Contact</a>
                </nav>

                {{-- Desktop Auth Buttons --}}
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="/login" class="text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200">Sign In</a>
                    <a href="/register"
                       class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium rounded-xl hover:shadow-lg hover:shadow-primary-200 transition-all duration-300 hover:-translate-y-0.5">
                        Get Started Free
                    </a>
                </div>

                {{-- Mobile Menu Button --}}
                <button id="mobileMenuBtn" class="lg:hidden text-gray-700 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobileMenu" class="lg:hidden hidden absolute top-full left-0 right-0 bg-white border-t border-gray-100 shadow-lg animate-slide-up">
            <div class="px-4 py-6 space-y-1">
                <a href="/" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-colors">
                    <i class="fas fa-home w-5"></i>
                    <span>Home</span>
                </a>
                <a href="/pricing" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-colors">
                    <i class="fas fa-tag w-5"></i>
                    <span>Pricing</span>
                </a>
                <a href="/about" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-colors">
                    <i class="fas fa-info-circle w-5"></i>
                    <span>About</span>
                </a>
                <a href="/contact" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-colors">
                    <i class="fas fa-envelope w-5"></i>
                    <span>Contact</span>
                </a>
                
                <div class="pt-4 mt-4 border-t border-gray-100 space-y-3">
                    <a href="/login" class="block px-4 py-3 text-center text-gray-700 font-medium hover:text-primary-600 hover:bg-gray-50 rounded-xl transition-colors">
                        Sign In
                    </a>
                    <a href="/register"
                       class="block px-4 py-3 text-center bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium rounded-xl hover:shadow-lg transition-all">
                        Get Started Free
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-gradient-to-b from-white to-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12">
                {{-- Brand Section --}}
                <div class="lg:col-span-1">
                    <a href="/" class="flex items-center space-x-2 mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-primary-600 to-primary-800 rounded-xl flex items-center justify-center">
                            <image accesskey="logo" src="/images/logo.png" class="w-14 h-14"/>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">SmartRent</span>
                    </a>
                    <p class="text-gray-600 mt-4">
                        Simplify property management with our all-in-one platform for landlords and tenants.
                    </p>
                    <div class="flex space-x-4 mt-6">
                        <a href="#" class="w-10 h-10 bg-gray-100 hover:bg-primary-50 text-gray-600 hover:text-primary-600 rounded-xl flex items-center justify-center transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-100 hover:bg-primary-50 text-gray-600 hover:text-primary-600 rounded-xl flex items-center justify-center transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-100 hover:bg-primary-50 text-gray-600 hover:text-primary-600 rounded-xl flex items-center justify-center transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-100 hover:bg-primary-50 text-gray-600 hover:text-primary-600 rounded-xl flex items-center justify-center transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Product</h3>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-gray-600 hover:text-primary-600 transition-colors">Features</a></li>
                        <li><a href="/pricing" class="text-gray-600 hover:text-primary-600 transition-colors">Pricing</a></li>
                        <li><a href="/about" class="text-gray-600 hover:text-primary-600 transition-colors">About</a></li>
                        
                    </ul>
                </div>

                {{-- Company --}}
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="/about" class="text-gray-600 hover:text-primary-600 transition-colors">About Us</a></li>
                        <li><a href="/about" class="text-gray-600 hover:text-primary-600 transition-colors">Careers</a></li>
                        <li><a href="/about" class="text-gray-600 hover:text-primary-600 transition-colors">Blog</a></li>
                        <li><a href="/about" class="text-gray-600 hover:text-primary-600 transition-colors">Press</a></li>
                    </ul>
                </div>

                {{-- Support --}}
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Support</h3>
                    <ul class="space-y-3">
                        <li><a href="/contact" class="text-gray-600 hover:text-primary-600 transition-colors">Help Center</a></li>
                        <li><a href="/contact" class="text-gray-600 hover:text-primary-600 transition-colors">Contact Us</a></li>
                        <li><a href="/contact" class="text-gray-600 hover:text-primary-600 transition-colors">Privacy Policy</a></li>
                        <li><a href="/contact" class="text-gray-600 hover:text-primary-600 transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            {{-- Newsletter --}}
            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Stay in the loop</h3>
                        <p class="text-gray-600">Subscribe to our newsletter for the latest updates.</p>
                    </div>
                    <form class="flex gap-2 max-w-md">
                        <input type="email" 
                               placeholder="Enter your email" 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                        <button type="submit" 
                                class="px-5 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium rounded-xl hover:shadow-lg transition-shadow">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            {{-- Copyright --}}
            <div class="mt-12 pt-8 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-600 text-sm">
                    © {{ date('Y') }} SmartRent. All rights reserved.
                </p>
                <div class="flex items-center space-x-6 text-sm">
                    <a href="/" class="text-gray-600 hover:text-primary-600 transition-colors">Privacy Policy</a>
                    <a href="/" class="text-gray-600 hover:text-primary-600 transition-colors">Terms of Service</a>
                    <a href="/" class="text-gray-600 hover:text-primary-600 transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Back to Top Button --}}
    <button id="backToTop" 
            class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-br from-primary-600 to-primary-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 opacity-0 translate-y-10 hover:-translate-y-1 z-40">
        <i class="fas fa-chevron-up"></i>
    </button>

    {{-- JavaScript --}}
    <script>
        // Mobile Menu Toggle
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const body = document.body;

        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('animate-slide-up');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (event) => {
            if (!mobileMenu.contains(event.target) && !mobileBtn.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Close mobile menu on escape key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                mobileMenu.classList.add('hidden');
            }
        });

        // Back to Top Button
        const backToTopBtn = document.getElementById('backToTop');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.remove('opacity-0', 'translate-y-10');
                backToTopBtn.classList.add('opacity-100', 'translate-y-0');
            } else {
                backToTopBtn.classList.remove('opacity-100', 'translate-y-0');
                backToTopBtn.classList.add('opacity-0', 'translate-y-10');
            }
        });

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add active class to current page in navigation
        const currentPath = window.location.pathname;
        document.querySelectorAll('nav a').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('text-primary-600');
                link.classList.add('font-semibold');
            }
        });
    </script>
</body>
</html>