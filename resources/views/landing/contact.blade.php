@extends('layouts.landing')

@section('title', 'Contact Us - SmartRent')
@section('description', 'Get in touch with the SmartRent team. We\'re here to help with all your property management needs.')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-primary-500 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 left-0 w-1/3 h-1/3 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
    </div>
    
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
            Get in Touch
        </h1>
        <p class="text-xl text-primary-100 max-w-3xl mx-auto mb-8">
            Have questions about SmartRent? We're here to help you with anything you need.
        </p>
        
        <!-- Contact Stats -->
        <div class="inline-flex flex-wrap justify-center gap-6 md:gap-10 mb-8 p-6 bg-white/10 backdrop-blur-sm rounded-2xl">
            <div class="text-center">
                <div class="text-2xl font-bold text-white">24/7</div>
                <div class="text-primary-200 text-sm">Support Available</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white">1-2 hours</div>
                <div class="text-primary-200 text-sm">Avg Response Time</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white">98%</div>
                <div class="text-primary-200 text-sm">Satisfaction Rate</div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Contact Information -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Support Card -->
                <div class="bg-gradient-to-br from-primary-50 to-white border border-primary-100 rounded-2xl p-8">
                    <div class="w-14 h-14 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-headset text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Support Center</h3>
                    <p class="text-gray-600 mb-6">
                        Our support team is available 24/7 to help with any questions or issues.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-primary-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email Us</p>
                                <a href="mailto:support@smartrent.com" class="font-medium text-gray-900 hover:text-primary-600 transition-colors">
                                    support@smartrent.com
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-primary-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Call Us</p>
                                <a href="tel:+18001234567" class="font-medium text-gray-900 hover:text-primary-600 transition-colors">
                                    +1 (800) 123-4567
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Card -->
                <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-2xl p-8">
                    <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Sales Inquiries</h3>
                    <p class="text-gray-600 mb-6">
                        Interested in our enterprise plans or need a custom solution?
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email Sales</p>
                                <a href="mailto:sales@smartrent.com" class="font-medium text-gray-900 hover:text-blue-600 transition-colors">
                                    sales@smartrent.com
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-calendar text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Book a Demo</p>
                                <a href="/demo" class="font-medium text-gray-900 hover:text-blue-600 transition-colors">
                                    Schedule a Call
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Office Hours -->
                <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-100 rounded-2xl p-8">
                    <div class="w-14 h-14 bg-gray-800 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Office Hours</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-gray-600">Monday - Friday</span>
                            <span class="font-medium text-gray-900">9:00 AM - 6:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-gray-600">Saturday</span>
                            <span class="font-medium text-gray-900">10:00 AM - 4:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Sunday</span>
                            <span class="font-medium text-gray-900">Emergency Support Only</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-2xl p-8 shadow-lg">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Send us a Message</h2>
                    <p class="text-gray-600 mb-8">
                        Fill out the form below and we'll get back to you as soon as possible.
                    </p>

                    <form action="/contact/submit" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Success/Error Messages -->
                        @if(session('success'))
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-exclamation text-red-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-red-800">Please fix the following errors:</p>
                                    <ul class="mt-2 text-sm text-red-700">
                                        @foreach($errors->all() as $error)
                                            <li class="flex items-center mt-1">
                                                <i class="fas fa-circle text-xs mr-2"></i>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="name" 
                                           value="{{ old('name') }}"
                                           class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                           placeholder="Enter your full name"
                                           required>
                                </div>
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                           placeholder="your@email.com"
                                           required>
                                </div>
                            </div>

                            <!-- Phone Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number <span class="text-gray-400">(Optional)</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="tel" 
                                           name="phone" 
                                           value="{{ old('phone') }}"
                                           class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                           placeholder="+1 (123) 456-7890">
                                </div>
                            </div>

                            <!-- Subject Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Subject <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-tag text-gray-400"></i>
                                    </div>
                                    <select name="subject" 
                                            class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors appearance-none"
                                            required>
                                        <option value="">Select a subject</option>
                                        <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                        <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>Technical Support</option>
                                        <option value="sales" {{ old('subject') == 'sales' ? 'selected' : '' }}>Sales Inquiry</option>
                                        <option value="billing" {{ old('subject') == 'billing' ? 'selected' : '' }}>Billing Question</option>
                                        <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>Feedback/Suggestion</option>
                                        <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Partnership Opportunity</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Your Message <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <textarea name="message" 
                                          rows="6"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                          placeholder="Tell us how we can help you..."
                                          required>{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <!-- Privacy Checkbox -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       name="privacy" 
                                       id="privacy"
                                       class="w-4 h-4 border-gray-300 rounded focus:ring-primary-500 text-primary-600"
                                       required>
                            </div>
                            <label for="privacy" class="ml-3 text-sm text-gray-600">
                                I agree to the <a href="/privacy" class="text-primary-600 hover:text-primary-700 font-medium">Privacy Policy</a> 
                                and allow SmartRent to contact me regarding my inquiry.
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center space-x-4">
                            <button type="submit" 
                                    class="px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium rounded-xl hover:shadow-lg hover:shadow-primary-200 transition-all duration-300 hover:-translate-y-0.5 inline-flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Message
                            </button>
                            <span class="text-sm text-gray-500">
                                We typically respond within 1-2 business hours
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Frequently Asked Questions
            </h2>
            <p class="text-lg text-gray-600">
                Quick answers to common questions
            </p>
        </div>

        <div class="space-y-4">
            <!-- FAQ Item 1 -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:border-primary-200 transition-colors">
                <button class="flex items-center justify-between w-full text-left group" onclick="toggleFAQ(this)">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                        How quickly will I get a response?
                    </h3>
                    <i class="fas fa-chevron-down text-gray-400 group-hover:text-primary-600 transition-colors"></i>
                </button>
                <div class="hidden mt-4">
                    <p class="text-gray-600">
                        We typically respond to all inquiries within 1-2 business hours during our office hours. 
                        For urgent matters, you can call our 24/7 support line for immediate assistance.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:border-primary-200 transition-colors">
                <button class="flex items-center justify-between w-full text-left group" onclick="toggleFAQ(this)">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                        Do you offer phone support?
                    </h3>
                    <i class="fas fa-chevron-down text-gray-400 group-hover:text-primary-600 transition-colors"></i>
                </button>
                <div class="hidden mt-4">
                    <p class="text-gray-600">
                        Yes! You can reach our support team at +1 (800) 123-4567. Phone support is available 
                        24/7 for urgent issues, and during business hours for general inquiries.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:border-primary-200 transition-colors">
                <button class="flex items-center justify-between w-full text-left group" onclick="toggleFAQ(this)">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                        Can I schedule a demo of SmartRent?
                    </h3>
                    <i class="fas fa-chevron-down text-gray-400 group-hover:text-primary-600 transition-colors"></i>
                </button>
                <div class="hidden mt-4">
                    <p class="text-gray-600">
                        Absolutely! You can book a personalized demo with our sales team by emailing 
                        <a href="mailto:sales@smartrent.com" class="text-primary-600 hover:text-primary-700 font-medium">sales@smartrent.com</a> 
                        or using our <a href="/demo" class="text-primary-600 hover:text-primary-700 font-medium">demo scheduling tool</a>.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:border-primary-200 transition-colors">
                <button class="flex items-center justify-between w-full text-left group" onclick="toggleFAQ(this)">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                        What information should I include in my message?
                    </h3>
                    <i class="fas fa-chevron-down text-gray-400 group-hover:text-primary-600 transition-colors"></i>
                </button>
                <div class="hidden mt-4">
                    <p class="text-gray-600">
                        Please include your name, contact information, and a detailed description of your inquiry. 
                        If you're reporting an issue, include steps to reproduce it, screenshots if possible, 
                        and your account information.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary-600 to-primary-700">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-block p-2 bg-white/10 rounded-2xl backdrop-blur-sm mb-8">
            <span class="text-white font-medium px-4 py-2">Ready to get started?</span>
        </div>
        <h2 class="text-3xl font-bold text-white mb-6">
            Still have questions?
        </h2>
        <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
            Explore our help center or start a live chat with our support team.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/help" 
               class="px-8 py-4 bg-white text-primary-700 font-semibold rounded-xl hover:bg-primary-50 transition-all duration-300 hover:-translate-y-1 shadow-lg hover:shadow-xl inline-flex items-center justify-center">
                <i class="fas fa-question-circle mr-2"></i>
                <span>Visit Help Center</span>
            </a>
            <a href="javascript:void(0)" onclick="startLiveChat()" 
               class="px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-all duration-300 inline-flex items-center justify-center">
                <i class="fas fa-comment-dots mr-2"></i>
                <span>Start Live Chat</span>
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // FAQ Toggle Function
     // FAQ Toggle Function
    function toggleFAQ(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('i');
        
        // Toggle content visibility
        content.classList.toggle('hidden');
        
        // Toggle icon
        if (icon.classList.contains('fa-chevron-down')) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }

    // Initialize FAQ toggle with event delegation
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to all FAQ toggle buttons
        document.querySelectorAll('.bg-white.rounded-2xl button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const content = this.nextElementSibling;
                const icon = this.querySelector('i');
                
                // Toggle content visibility
                content.classList.toggle('hidden');
                
                // Toggle icon
                if (icon.classList.contains('fa-chevron-down')) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
        });
    });

    // Live Chat Simulation
    function startLiveChat() {
        alert('Starting live chat...\n\nOur support team will be with you shortly!\n\nIn the meantime, you can email us at support@smartrent.com');
    }

    // Phone number formatting
    const phoneInput = document.querySelector('input[type="tel"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = '+' + value;
            }
            if (value.length > 4) {
                value = value.replace(/(\+\d{1})(\d{3})(\d{0,3})/, '$1 ($2) $3');
            }
            if (value.length > 9) {
                value = value.replace(/(\+\d{1}\s\(\d{3}\)\s)(\d{3})(\d{0,4})/, '$1$2-$3');
            }
            e.target.value = value;
        });
    }

    // Form validation
    const contactForm = document.querySelector('form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            // Prevent default for demo
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
            submitBtn.disabled = true;
            
            // Simulate form submission
            setTimeout(() => {
                alert('Thank you for your message! We\'ll get back to you within 1-2 business hours.');
                this.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    }

    // Close FAQ when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.bg-white.rounded-2xl')) {
            document.querySelectorAll('.bg-white.rounded-2xl .hidden + div').forEach(content => {
                content.classList.add('hidden');
                const icon = content.previousElementSibling.querySelector('i');
                if (icon && icon.classList.contains('fa-chevron-up')) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
        }
    });
</script>
@endpush