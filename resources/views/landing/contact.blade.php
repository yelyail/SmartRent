@extends('layouts.landing')

@section('content')
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 text-center">
            Contact Us
        </h1>
        <p class="text-gray-600 text-center mt-3">
            We’re here to help with anything you need.
        </p>

        <form action="/contact/submit" method="POST" class="mt-12 bg-white p-8 rounded-lg border border-gray-200">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-gray-700 font-medium">Full Name</label>
                    <input type="text" name="name" class="mt-2 w-full border-gray-300 rounded-lg" required>
                </div>

                <div>
                    <label class="text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" class="mt-2 w-full border-gray-300 rounded-lg" required>
                </div>
            </div>

            <div class="mt-6">
                <label class="text-gray-700 font-medium">Message</label>
                <textarea name="message" rows="5" class="mt-2 w-full border-gray-300 rounded-lg" required></textarea>
            </div>

            <button class="mt-6 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                Send Message
            </button>
        </form>
    </div>
</section>
@endsection
