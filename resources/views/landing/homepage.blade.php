@extends('layouts.landing')

@section('content')
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-gray-900">
            Smarter Property Management, Made Simple
        </h1>
        <p class="mt-4 text-lg text-gray-600">
            All-in-one platform for landlords and tenants — leasing, payments, maintenance and analytics.
        </p>

        <div class="mt-8 flex justify-center space-x-4">
            <a href="/register" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Get Started
            </a>
            <a href="/pricing" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                View Pricing
            </a>
        </div>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-900 text-center">
            Everything You Need to Manage Rentals
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mt-12">
            <div class="p-6 bg-white rounded-lg border border-gray-200">
                <i class="fas fa-home text-3xl text-blue-600"></i>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Properties</h3>
                <p class="text-gray-600 mt-2">Easily upload and manage properties, units, and tenants.</p>
            </div>

            <div class="p-6 bg-white rounded-lg border border-gray-200">
                <i class="fas fa-file-invoice-dollar text-3xl text-blue-600"></i>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Payments</h3>
                <p class="text-gray-600 mt-2">Generate invoices, track payments, and avoid missed rent.</p>
            </div>

            <div class="p-6 bg-white rounded-lg border border-gray-200">
                <i class="fas fa-tools text-3xl text-blue-600"></i>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Maintenance</h3>
                <p class="text-gray-600 mt-2">Submit, assign, and track maintenance requests in real time.</p>
            </div>
        </div>
    </div>
</section>
@endsection
@section('title', 'SmartRent — Rental Management')