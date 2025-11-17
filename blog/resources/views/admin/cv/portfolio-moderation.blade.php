@extends('layouts.app')

@section('title', 'Portfolio Moderation')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">📋 Community Portfolio Submissions</h1>
        <p class="text-gray-600">Review and manage community portfolio submissions</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            ❌ {{ session('error') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-blue-600">{{ $projects->total() }}</div>
            <div class="text-sm text-gray-600">Total Submissions</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-green-600">{{ $projects->where('is_approved', true)->count() }}</div>
            <div class="text-sm text-gray-600">Approved</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-yellow-600">{{ $projects->where('is_approved', false)->count() }}</div>
            <div class="text-sm text-gray-600">Rejected</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <a href="{{ route('admin.cv.portfolio-stats', ['cv_id' => $cvId]) }}" class="text-blue-600 hover:text-blue-800">
                📊 View Stats
            </a>
        </div>
    </div>

    <!-- Submissions List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($projects->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Website</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Submitted</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($projects as $project)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $project->project_name }}</div>
                                @if($project->description)
                                    <div class="text-sm text-gray-600">{{ $project->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($project->submission_email)
                                    <a href="mailto:{{ $project->submission_email }}" class="text-blue-600 hover:underline">
                                        {{ $project->submission_email }}
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($project->submission_website)
                                    <a href="{{ $project->submission_website }}" target="_blank" class="text-blue-600 hover:underline">
                                        Visit
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($project->is_approved)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">✅ Approved</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">❌ Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $project->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    @if(!$project->is_approved)
                                        <form method="POST" action="{{ route('admin.cv.portfolio-approve', $project->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded text-xs hover:bg-green-600">
                                                Approve
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.cv.portfolio-reject', $project->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">
                                                Reject
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.cv.portfolio-delete', $project->id) }}" class="inline" onsubmit="return confirm('Delete this submission?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t">
                {{ $projects->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center text-gray-500">
                <p class="text-lg">No community submissions yet.</p>
                <p class="text-sm">Submissions will appear here when users submit via the portfolio form.</p>
            </div>
        @endif
    </div>

    <div class="mt-8">
        <a href="{{ route('admin.cv.dashboard', ['cv_id' => $cvId]) }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Dashboard
        </a>
    </div>
</div>
@endsection
