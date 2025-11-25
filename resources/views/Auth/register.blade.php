<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartRent - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Vite compiled CSS -->
    @vite('resources/css/app.css')
    @vite(['resources/js/register.js'])

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="font-sans bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <!-- Logo and Header -->
        <div class="text-center rounded-2xl ">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl shadow-lg">
                <image src="{{ asset('images/logo.png') }}" alt="SmartRent Logo">
            </div>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Join SmartRent</h2>
                <p class="text-gray-600">Create your account to get started</p>
            </div>

            <form class="space-y-5" id="registrationForm" method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data">
                @csrf    
                <!-- Personal Information Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="John"
                            >
                        </div>
                    </div>
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="middle_name" 
                                name="middle_name" 
                                class="w-full pl-4 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Michael"
                            >
                        </div>
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                required
                                class="w-full pl-4 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Doe"
                            >
                        </div>
                    </div>
                </div>

                <!-- Address Field -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                        </div>
                        <input 
                            type="text" 
                            id="address" 
                            name="address" 
                            required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            placeholder="123 Main Street, City, State, ZIP"
                        >
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="phone_num" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input 
                                type="tel" 
                                id="phone_num" 
                                name="phone_num" 
                                inputMode="numeric"
                                maxLength="11"
                                placeholder="09123456789"
                                required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            >
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
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
                                placeholder="john.doe@example.com"
                            >
                        </div>
                    </div>
                </div>

                <!-- Role Field -->
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-briefcase text-gray-400"></i>
                        </div>
                        <select 
                            id="role" 
                            name="role" 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        >
                            <option value="" disabled selected>I am a ..</option>
                            <option value="landlord">Landlord</option>
                            <option value="tenants">Tenant</option>
                        </select>
                    </div>
                </div>

                <!-- Password Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
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
                                onclick="togglePassword('password')"
                            >
                                <i id="password-toggle" class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors"></i>
                            </button>
                        </div>
                        <div id="password-strength" class="h-1 rounded-full mt-2 bg-gray-200"></div>
                        <p class="text-xs text-gray-500 mt-2">Must be at least 12 characters with uppercase, lowercase, and number</p>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required
                                class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="*********"
                            >
                            <button 
                                type="button" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePassword('password_confirmation')"
                            >
                                <i id="confirm-password-toggle" class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- KYC Document Upload Section -->
                <div class="border-t pt-6 mt-4">
                    <h3 class="text-lg font-medium text-gray-900">Document Upload</h3>
                    <p class="text-sm text-gray-600 mb-4">Please upload required documents for verification</p>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="doc_type" class="block text-sm font-medium text-gray-700 mb-2">Document Type *</label>
                                <select 
                                    id="doc_type" 
                                    name="doc_type" 
                                    required
                                    class="w-full py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                >
                                    <option value="">Select document type</option>
                                    <option value="passport">Passport</option>
                                    <option value="driver_license">Driver's License</option>
                                    <option value="national_id">National ID</option>
                                    <option value="utility_bill">Utility Bill</option>
                                </select>
                            </div>
                        
                            <div>
                                <label for="doc_name" class="block text-sm font-medium text-gray-700 mb-2">Document Name *</label>
                                <input 
                                    type="text" 
                                    id="doc_name" 
                                    name="doc_name" 
                                    required
                                    class="w-full py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="e.g., Passport Front Page"
                                >
                            </div>
                        </div>
                        
                        <!-- Main Document Upload - Single File Only -->
                        <div>
                            <label for="doc_path" class="block text-sm font-medium text-gray-700 mb-2">Upload Document *</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="doc_path" id="docFileLabel" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all duration-200">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-500">PDF, PNG, JPG (MAX. 5MB)</p>
                                    </div>
                                    <input id="doc_path" name="doc_path" type="file" class="hidden" accept=".pdf,.png,.jpg,.jpeg" required />
                                </label>
                            </div>
                            <div id="docFileList" class="mt-2 space-y-2"></div>
                        </div>
                        
                        <!-- Proof of Income (Optional) -->
                        <div>
                            <label for="proof_of_income" class="block text-sm font-medium text-gray-700 mb-2">Proof of Income (Optional)</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="proof_of_income" id="proofOfIncomeLabel" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all duration-200">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fas fa-file-invoice-dollar text-gray-400 text-2xl mb-2"></i>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-500">PDF, PNG, JPG (MAX. 5MB)</p>
                                    </div>
                                    <input id="proof_of_income" name="proof_of_income" type="file" class="hidden" accept=".pdf,.png,.jpg,.jpeg" />
                                </label>
                            </div>
                            <div id="proofOfIncomeFileList" class="mt-2 space-y-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Create Account Button -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-3 px-4 rounded-xl font-medium hover:from-blue-700 hover:to-indigo-800 focus:ring-4 focus:ring-blue-200 transition-all duration-200 flex items-center justify-center space-x-2 shadow-md hover:shadow-lg"
                >
                    <span>Create Account</span>
                    <i class="fas fa-user-plus text-sm"></i>
                </button>
            </form>
            <!-- Divider -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    Already have an account?  
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium transition-colors ml-1">
                        Sign in
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-xs text-gray-500">
                &copy; SmartRent. All rights reserved.
            </p>
        </div>
    </div>
    @if(session('success'))
        <div data-success="{{ session('success') }}"></div>
    @endif

    @if(session('error'))
        <div data-error="{{ session('error') }}"></div>
    @endif

    @if($errors->any())
        <div data-error="{{ implode(' ', $errors->all()) }}"></div>
    @endif

   <script src="{{ asset('js/register.js') }}"></script>
</body>
</html>