@extends('layouts.admin')

@section('title', 'Teachers')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">👨‍🏫 Teachers</h1>
            <a href="{{ route('school.teachers.create') }}" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition">
                <i class="fa-solid fa-plus"></i> Add Teacher
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qualification</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($teachers as $teacher)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm font-medium">{{ $teacher->full_name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $teacher->email }}</td>
                            <td class="px-6 py-4 text-sm">{{ $teacher->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $teacher->qualification ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $teacher->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($teacher->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('school.teachers.edit', $teacher) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('school.teachers.destroy', $teacher) }}" method="POST" class="inline" onsubmit="return confirm('Delete this teacher?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                No teachers found. <a href="{{ route('school.teachers.create') }}" class="text-blue-600 hover:underline">Add one now</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
