@extends('layouts.app')

@section('title', 'Portfolio Statistics')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">📊 Portfolio Statistics</h1>
        <p class="text-gray-600">Overview of CV portfolio data</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Total Projects -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Total Projects</p>
                    <p class="text-4xl font-bold text-blue-600">{{ $total + $direct }}</p>
                </div>
                <div class="text-5xl text-blue-100">📦</div>
            </div>
        </div>

        <!-- Direct (Admin) Projects -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Admin Created</p>
                    <p class="text-4xl font-bold text-indigo-600">{{ $direct }}</p>
                </div>
                <div class="text-5xl text-indigo-100">👤</div>
            </div>
        </div>

        <!-- Community Submissions -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Community Submissions</p>
                    <p class="text-4xl font-bold text-purple-600">{{ $total }}</p>
                </div>
                <div class="text-5xl text-purple-100">👥</div>
            </div>
        </div>

        <!-- Approved Submissions -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Approved</p>
                    <p class="text-4xl font-bold text-green-600">{{ $approved }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $total > 0 ? round(($approved / $total) * 100) : 0 }}% approval rate</p>
                </div>
                <div class="text-5xl text-green-100">✅</div>
            </div>
        </div>

        <!-- Pending Review -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Pending Review</p>
                    <p class="text-4xl font-bold text-yellow-600">{{ $pending }}</p>
                </div>
                <div class="text-5xl text-yellow-100">⏳</div>
            </div>
        </div>

        <!-- Overall Portfolio Health -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Portfolio Quality</p>
                    <p class="text-4xl font-bold text-emerald-600">
                        @php
                            $quality = ($approved + $direct) > 0 ? round((($approved + $direct) / ($total + $direct)) * 100) : 100;
                        @endphp
                        {{ $quality }}%
                    </p>
                </div>
                <div class="text-5xl text-emerald-100">⭐</div>
            </div>
        </div>
    </div>

    <!-- Detailed Breakdown -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">📈 Breakdown</h2>
        
        <div class="space-y-4">
            <div>
                <div class="flex justify-between mb-2">
                    <span>Admin Projects (Direct)</span>
                    <span class="font-semibold">{{ $direct }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $total + $direct > 0 ? ($direct / ($total + $direct)) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between mb-2">
                    <span>Community Submissions (Approved)</span>
                    <span class="font-semibold">{{ $approved }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $total > 0 ? ($approved / ($total + $direct)) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between mb-2">
                    <span>Community Submissions (Pending)</span>
                    <span class="font-semibold">{{ $pending }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $total > 0 ? ($pending / ($total + $direct)) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 flex gap-4">
        <a href="{{ route('admin.cv.portfolio-moderation', ['cv_id' => $cvId]) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            ← Review Submissions
        </a>
        <a href="{{ route('admin.cv.dashboard', ['cv_id' => $cvId]) }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
            Dashboard
        </a>
    </div>
</div>
@endsection
