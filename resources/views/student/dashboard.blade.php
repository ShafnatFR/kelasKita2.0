@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6">Student Dashboard</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- My Classes -->
            <div class="bg-blue-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-2">My Classes</h2>
                <p class="text-3xl font-bold text-blue-600">0</p>
            </div>

            <!-- In Progress -->
            <div class="bg-green-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-2">In Progress</h2>
                <p class="text-3xl font-bold text-green-600">0</p>
            </div>

            <!-- Completed -->
            <div class="bg-purple-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-2">Completed</h2>
                <p class="text-3xl font-bold text-purple-600">0</p>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Recent Activity</h2>
            <div class="bg-gray-50 p-6 rounded-lg">
                <p class="text-gray-600">No recent activity</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <a href="/" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Browse Classes
            </a>
            <a href="/keranjang" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                View Cart
            </a>
        </div>
    </div>
</div>
@endsection
