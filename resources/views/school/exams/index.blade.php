@extends('layouts.admin')

@section('title', 'Exams')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Exams</h1>
            <a href="{{ route('school.exams.create') }}" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-plus"></i> Create Exam
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Max Marks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($exams as $exam)
                    <tr>
                        <td class="px-6 py-4 text-sm font-semibold">{{ $exam->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $exam->class->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $exam->start_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm">{{ $exam->end_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm">{{ $exam->max_marks }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $exam->status == 'upcoming' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $exam->status == 'ongoing' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $exam->status == 'completed' ? 'bg-green-100 text-green-700' : '' }}">
                                {{ ucfirst($exam->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('school.exams.show', $exam) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('school.exams.edit', $exam) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <a href="{{ route('school.exams.results.view', $exam) }}" class="text-green-600 hover:text-green-800 mr-2">
                                <i class="fa-solid fa-table"></i>
                            </a>
                            <form action="{{ route('school.exams.destroy', $exam) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Delete this exam?')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No exams found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $exams->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
