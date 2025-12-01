@extends('layouts.landing')

@section('content')
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 text-center">
            Simple and Transparent Pricing
        </h1>
        <p class="text-gray-600 text-center mt-3">
            No hidden fees. Choose the plan that suits your rental needs.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
            {{-- Starter --}}
            <div class="border border-gray-200 rounded-lg p-6 bg-white">
                <h3 class="text-xl font-semibold text-gray-900">Starter</h3>
                <p class="text-gray-600 mt-2">Ideal for single landlords with 1–3 units.</p>
                <p class="text-4xl font-bold text-gray-900 mt-6">₱9/mo</p>
                <ul class="mt-6 space-y-3 text-gray-700">
                    <li><i class="fas fa-check text-green-500"></i> 3 Properties</li>
                    <li><i class="fas fa-check text-green-500"></i> Rent Collection</li>
                    <li><i class="fas fa-check text-green-500"></i> Maintenance Tracking</li>
                </ul>
                <a href="/register" class="mt-6 block text-center bg-blue-600 text-white py-2 rounded-lg">
                    Get Started
                </a>
            </div>

            {{-- Pro --}}
            <div class="border border-gray-200 rounded-lg p-6 bg-white shadow-lg">
                <h3 class="text-xl font-semibold text-gray-900">Pro</h3>
                <p class="text-gray-600 mt-2">Best for growing rental businesses.</p>
                <p class="text-4xl font-bold text-gray-900 mt-6">₱29/mo</p>
                <ul class="mt-6 space-y-3 text-gray-700">
                    <li><i class="fas fa-check text-green-500"></i> Unlimited Properties</li>
                    <li><i class="fas fa-check text-green-500"></i> Automated Invoices</li>
                    <li><i class="fas fa-check text-green-500"></i> Priority Support</li>
                </ul>
                <a href="/register" class="mt-6 block text-center bg-blue-600 text-white py-2 rounded-lg">
                    Choose Pro
                </a>
            </div>

            {{-- Enterprise --}}
            <div class="border border-gray-200 rounded-lg p-6 bg-white">
                <h3 class="text-xl font-semibold text-gray-900">Enterprise</h3>
                <p class="text-gray-600 mt-2">For property companies and agencies.</p>
                <p class="text-4xl font-bold text-gray-900 mt-6">Custom</p>
                <ul class="mt-6 space-y-3 text-gray-700">
                    <li><i class="fas fa-check text-green-500"></i> API Integrations</li>
                    <li><i class="fas fa-check text-green-500"></i> Unlimited Users</li>
                    <li><i class="fas fa-check text-green-500"></i> Dedicated Support</li>
                </ul>
                <a href="/contact" class="mt-6 block text-center border border-gray-300 py-2 rounded-lg text-gray-700">
                    Contact Sales
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
@section('title', 'Pricing - SmartRent')