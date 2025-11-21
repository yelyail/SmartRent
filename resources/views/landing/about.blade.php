@extends('layouts.landing')

@section('content')
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 text-center">
            About SmartRent
        </h1>
        <p class="text-gray-600 text-center mt-3">
            Making rental management simple, transparent, and efficient.
        </p>

        <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Our Mission</h2>
                <p class="text-gray-600 mt-4 leading-relaxed">
                    SmartRent was built to eliminate the headaches of traditional property management.
                    With automated rent collection, digital leases, maintenance handling, and modern
                    communication tools, we help landlords and tenants operate smoothly.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-900">Why We Exist</h2>
                <p class="text-gray-600 mt-4 leading-relaxed">
                    Rental processes are often slow, manual, and prone to errors. Our platform
                    centralizes everything into a single dashboard — improving transparency, saving time,
                    and reducing cost for everyone involved.
                </p>
            </div>

        </div>
    </div>
</section>
@endsection
@section('title', 'About Us - SmartRent')