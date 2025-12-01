@extends('layouts.landing')

@section('title', 'About Us - SmartRent')
@section('description', 'Learn about SmartRent\'s mission to revolutionize property management through technology and innovation.')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-primary-500 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 left-0 w-1/3 h-1/3 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
    </div>
    
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                About SmartRent
            </h1>
            <p class="text-xl text-primary-100 max-w-3xl mx-auto mb-8">
                Revolutionizing property management through innovative technology and exceptional service.
            </p>
            
            <!-- Stats Banner -->
            <div class="inline-flex flex-wrap justify-center gap-6 md:gap-10 mb-8 p-6 bg-white/10 backdrop-blur-sm rounded-2xl">
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">2019</div>
                    <div class="text-primary-200 text-sm">Founded</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">5,000+</div>
                    <div class="text-primary-200 text-sm">Properties Managed</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">50+</div>
                    <div class="text-primary-200 text-sm">Team Members</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">98%</div>
                    <div class="text-primary-200 text-sm">Client Satisfaction</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Story -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center px-4 py-2 bg-primary-50 text-primary-700 rounded-full text-sm font-medium mb-6">
                    <i class="fas fa-rocket mr-2"></i>
                    Our Journey
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">
                    Building the Future of Property Management
                </h2>
                <p class="text-lg text-gray-600 mb-6">
                    SmartRent was founded in 2019 by a team of real estate professionals and technologists 
                    who were frustrated with the outdated tools and processes in property management.
                </p>
                <p class="text-lg text-gray-600 mb-8">
                    We recognized that landlords were spending too much time on administrative tasks, 
                    while tenants struggled with communication gaps and slow response times. Our mission 
                    was clear: create an all-in-one platform that makes rental management simple, transparent, 
                    and efficient for everyone.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Trusted by 2,500+ landlords</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-check text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">₱500M+ in rent processed</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="relative">
                <div class="bg-gradient-to-br from-primary-50 to-white border border-primary-100 rounded-2xl p-8">
                    <div class="w-16 h-16 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-home text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Our Vision</h3>
                    <p class="text-gray-600 mb-6">
                        To become the most trusted platform for property management worldwide, 
                        empowering landlords to grow their portfolios and providing tenants 
                        with exceptional living experiences.
                    </p>
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <div class="text-2xl font-bold text-primary-600">24/7</div>
                            <div class="text-sm text-gray-600">Customer Support</div>
                        </div>
                        <div class="flex-1">
                            <div class="text-2xl font-bold text-primary-600">99.9%</div>
                            <div class="text-sm text-gray-600">Platform Uptime</div>
                        </div>
                        <div class="flex-1">
                            <div class="text-2xl font-bold text-primary-600">4.8★</div>
                            <div class="text-sm text-gray-600">App Rating</div>
                        </div>
                    </div>
                </div>
                
                <!-- Decorative element -->
                <div class="absolute -top-6 -right-6 w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full blur-xl opacity-60 -z-10"></div>
            </div>
        </div>
    </div>
</section>

<!-- Timeline Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Our Journey
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Milestones in our mission to transform property management
            </p>
        </div>
        
        <div class="relative">
            <!-- Timeline line -->
            <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-0.5 bg-gradient-to-b from-primary-500 to-primary-300 hidden lg:block"></div>
            
            <div class="space-y-12">
                <!-- Timeline Item 1 -->
                <div class="relative lg:grid lg:grid-cols-2 lg:gap-8 items-center">
                    <div class="lg:text-right lg:pr-12">
                        <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-4">
                            2019
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Company Founded</h3>
                        <p class="text-gray-600">
                            SmartRent was born out of frustration with outdated property management tools.
                            Our founders identified the need for a modern, all-in-one solution.
                        </p>
                    </div>
                    
                    <!-- Timeline dot -->
                    <div class="hidden lg:block absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-primary-600 rounded-full border-4 border-white"></div>
                    
                    <div class="lg:pl-12 mt-6 lg:mt-0">
                        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-lightbulb text-blue-600"></i>
                            </div>
                            <p class="text-gray-600">
                                Started with just 3 team members and a vision to simplify rental management.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline Item 2 -->
                <div class="relative lg:grid lg:grid-cols-2 lg:gap-8 items-center">
                    <div class="lg:pl-12 lg:order-2">
                        <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-medium mb-4">
                            2020
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Platform Launch</h3>
                        <p class="text-gray-600">
                            Launched our MVP with core features: property listing, tenant screening, 
                            and digital lease agreements.
                        </p>
                    </div>
                    
                    <!-- Timeline dot -->
                    <div class="hidden lg:block absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-primary-600 rounded-full border-4 border-white"></div>
                    
                    <div class="lg:pr-12 lg:order-1 mt-6 lg:mt-0">
                        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-rocket text-green-600"></i>
                            </div>
                            <p class="text-gray-600">
                                Gained our first 500 customers and processed ₱50M in rental payments.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline Item 3 -->
                <div class="relative lg:grid lg:grid-cols-2 lg:gap-8 items-center">
                    <div class="lg:text-right lg:pr-12">
                        <div class="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-medium mb-4">
                            2021
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Mobile App Release</h3>
                        <p class="text-gray-600">
                            Launched iOS and Android apps, making property management accessible on the go.
                        </p>
                    </div>
                    
                    <!-- Timeline dot -->
                    <div class="hidden lg:block absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-primary-600 rounded-full border-4 border-white"></div>
                    
                    <div class="lg:pl-12 mt-6 lg:mt-0">
                        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-mobile-alt text-purple-600"></i>
                            </div>
                            <p class="text-gray-600">
                                Expanded team to 25 members and introduced maintenance request tracking.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline Item 4 -->
                <div class="relative lg:grid lg:grid-cols-2 lg:gap-8 items-center">
                    <div class="lg:pl-12 lg:order-2">
                        <div class="inline-flex items-center px-4 py-2 bg-orange-100 text-orange-700 rounded-full text-sm font-medium mb-4">
                            2022 - Present
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Rapid Growth</h3>
                        <p class="text-gray-600">
                            Expanded nationwide, introduced smart home integrations, and reached 5,000+ 
                            properties under management.
                        </p>
                    </div>
                    
                    <!-- Timeline dot -->
                    <div class="hidden lg:block absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-primary-600 rounded-full border-4 border-white"></div>
                    
                    <div class="lg:pr-12 lg:order-1 mt-6 lg:mt-0">
                        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-chart-line text-orange-600"></i>
                            </div>
                            <p class="text-gray-600">
                                Introduced AI-powered insights and automated rent collection features.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Meet Our Leadership Team
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                The passionate people behind SmartRent's success
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Team Member 1 -->
            <div class="group bg-gradient-to-br from-white to-gray-50 rounded-2xl p-8 border border-gray-200 hover:border-primary-200 transition-all duration-300 hover:shadow-xl text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-primary-100 to-primary-200 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-user text-primary-600 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Lorem Ipsum</h3>
                <p class="text-primary-600 font-medium mb-4">CEO & Co-founder</p>
                <p class="text-gray-600 text-sm">
                    Former real estate investor with 15+ years experience managing 500+ properties.
                </p>
                <div class="flex justify-center space-x-4 mt-6">
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>
            
            <!-- Team Member 2 -->
            <div class="group bg-gradient-to-br from-white to-gray-50 rounded-2xl p-8 border border-gray-200 hover:border-primary-200 transition-all duration-300 hover:shadow-xl text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-laptop-code text-blue-600 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Lorem Ipsum</h3>
                <p class="text-primary-600 font-medium mb-4">CTO & Co-founder</p>
                <p class="text-gray-600 text-sm">
                    Former Google engineer with expertise in scalable platforms and AI solutions.
                </p>
                <div class="flex justify-center space-x-4 mt-6">
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>
            
            <!-- Team Member 3 -->
            <div class="group bg-gradient-to-br from-white to-gray-50 rounded-2xl p-8 border border-gray-200 hover:border-primary-200 transition-all duration-300 hover:shadow-xl text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-green-100 to-green-200 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-chart-line text-green-600 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Lorem Ipsum</h3>
                <p class="text-primary-600 font-medium mb-4">COO</p>
                <p class="text-gray-600 text-sm">
                    Operations expert with 10+ years in proptech and customer experience management.
                </p>
                <div class="flex justify-center space-x-4 mt-6">
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Additional Info -->
        <div class="mt-16 text-center">
            <div class="inline-flex items-center px-6 py-3 bg-gray-100 rounded-full text-gray-700 font-medium">
                <i class="fas fa-users mr-2"></i>
                <span>50+ dedicated team members across engineering, support, and operations</span>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Our Core Values
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                The principles that guide everything we do
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Value 1 -->
            <div class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-shield-alt text-primary-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Trust & Transparency</h3>
                <p class="text-gray-600">
                    We believe in clear communication and honest dealings with both landlords and tenants.
                </p>
            </div>
            
            <!-- Value 2 -->
            <div class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-lightbulb text-green-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Innovation</h3>
                <p class="text-gray-600">
                    Continuously improving our platform to solve real problems in property management.
                </p>
            </div>
            
            <!-- Value 3 -->
            <div class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-hands-helping text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Customer Success</h3>
                <p class="text-gray-600">
                    We measure our success by how well we serve our customers' needs.
                </p>
            </div>
            
            <!-- Value 4 -->
            <div class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-bolt text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Efficiency</h3>
                <p class="text-gray-600">
                    Streamlining processes to save time and reduce costs for everyone involved.
                </p>
            </div>
            
            <!-- Value 5 -->
            <div class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-heart text-orange-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Passion</h3>
                <p class="text-gray-600">
                    Our team is passionate about making rental experiences better for all parties.
                </p>
            </div>
            
            <!-- Value 6 -->
            <div class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-globe text-indigo-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Accessibility</h3>
                <p class="text-gray-600">
                    Making property management tools accessible to landlords of all sizes.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary-600 to-primary-700">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-block p-2 bg-white/10 rounded-2xl backdrop-blur-sm mb-8">
            <span class="text-white font-medium px-4 py-2">Join our mission</span>
        </div>
        <h2 class="text-3xl font-bold text-white mb-6">
            Ready to Transform Your Property Management?
        </h2>
        <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
            Join thousands of landlords who trust SmartRent to manage their properties efficiently.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/register" 
               class="px-8 py-4 bg-white text-primary-700 font-semibold rounded-xl hover:bg-primary-50 transition-all duration-300 hover:-translate-y-1 shadow-lg hover:shadow-xl inline-flex items-center justify-center">
                <i class="fas fa-rocket mr-2"></i>
                <span>Start Free Trial</span>
            </a>
            <a href="/careers" 
               class="px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-all duration-300 inline-flex items-center justify-center">
                <i class="fas fa-briefcase mr-2"></i>
                <span>View Careers</span>
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Add interactive timeline effects
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to timeline items
        const timelineItems = document.querySelectorAll('.relative.lg\\:grid');
        
        timelineItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                const dot = this.querySelector('.bg-primary-600');
                if (dot) {
                    dot.style.transform = 'translateX(-50%) scale(1.2)';
                    dot.style.transition = 'transform 0.3s ease';
                }
            });
            
            item.addEventListener('mouseleave', function() {
                const dot = this.querySelector('.bg-primary-600');
                if (dot) {
                    dot.style.transform = 'translateX(-50%) scale(1)';
                }
            });
        });
        
        // Animate values on scroll
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);
        
        // Observe timeline items and values
        document.querySelectorAll('.bg-white.rounded-2xl').forEach(card => {
            observer.observe(card);
        });
    });
</script>
@endpush