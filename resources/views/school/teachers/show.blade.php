@extends('layouts.admin')

@section('title', 'Teacher Details')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('school.teachers.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">👨‍🏫 Teacher Details</h1>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('school.teachers.edit', $teacher) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </a>
                <form action="{{ route('school.teachers.destroy', $teacher) }}" method="POST" class="inline" onsubmit="return confirm('Delete this teacher permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                        <i class="fa-solid fa-trash-can"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Teacher Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-blue-50 to-teal-50 px-6 py-8 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-teal-500 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ strtoupper(substr($teacher->first_name, 0, 1)) }}{{ strtoupper(substr($teacher->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $teacher->full_name }}</h2>
                        <p class="text-gray-600">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $teacher->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($teacher->status) }}
                            </span>
                            <span class="ml-2 text-sm text-gray-500">Teacher</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Personal Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-400">Full Name</p>
                            <p class="text-sm font-medium text-gray-800">{{ $teacher->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Email Address</p>
                            <p class="text-sm font-medium text-gray-800">
                                <a href="mailto:{{ $teacher->email }}" class="text-blue-600 hover:underline">{{ $teacher->email }}</a>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Phone Number</p>
                            <p class="text-sm font-medium text-gray-800">
                                @if($teacher->phone)
                                    <a href="tel:{{ $teacher->phone }}" class="text-blue-600 hover:underline">{{ $teacher->phone }}</a>
                                @else
                                    <span class="text-gray-400">Not provided</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Gender</p>
                            <p class="text-sm font-medium text-gray-800">{{ ucfirst($teacher->gender ?? 'Not specified') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Date of Birth</p>
                            <p class="text-sm font-medium text-gray-800">
                                @if($teacher->date_of_birth)
                                    {{ \Carbon\Carbon::parse($teacher->date_of_birth)->format('d M Y') }}
                                    ({{ \Carbon\Carbon::parse($teacher->date_of_birth)->age }} years)
                                @else
                                    <span class="text-gray-400">Not provided</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Professional Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-400">Qualification</p>
                            <p class="text-sm font-medium text-gray-800">{{ $teacher->qualification ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Joining Date</p>
                            <p class="text-sm font-medium text-gray-800">
                                @if($teacher->joining_date)
                                    {{ \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') }}
                                @else
                                    <span class="text-gray-400">Not specified</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Address</p>
                            <p class="text-sm font-medium text-gray-800">{{ $teacher->address ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $teacher->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($teacher->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Member Since</p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($teacher->created_at)->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-wrap gap-3">
                <a href="{{ route('school.teachers.index') }}" class="text-gray-600 hover:text-gray-800 text-sm flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left"></i> Back to List
                </a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('school.teachers.edit', $teacher) }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </a>
                <span class="text-gray-300">|</span>
                <form action="{{ route('school.teachers.destroy', $teacher) }}" method="POST" class="inline" onsubmit="return confirm('Delete this teacher permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm flex items-center gap-1">
                        <i class="fa-solid fa-trash-can"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
