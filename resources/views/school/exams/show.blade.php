@extends('layouts.app')

@section('title', $exam->name)

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('school.exams.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $exam->name }}</h1>
            <span class="ml-4 px-3 py-1 rounded-full text-sm font-semibold
                {{ $exam->status == 'upcoming' ? 'bg-blue-100 text-blue-700' : '' }}
                {{ $exam->status == 'ongoing' ? 'bg-yellow-100 text-yellow-700' : '' }}
                {{ $exam->status == 'completed' ? 'bg-green-100 text-green-700' : '' }}">
                {{ ucfirst($exam->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">Class</p>
                <p class="font-bold">{{ $exam->class->name ?? 'N/A' }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">Start Date</p>
                <p class="font-bold">{{ $exam->start_date->format('d M Y') }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">End Date</p>
                <p class="font-bold">{{ $exam->end_date->format('d M Y') }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">Max Marks</p>
                <p class="font-bold">{{ $exam->max_marks }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Results</h3>
                <a href="{{ route('school.exams.results.view', $exam) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                    <i class="fa-solid fa-edit"></i> Enter Results
                </a>
            </div>

            @if($exam->results->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Marks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Max</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($exam->results as $result)
                    <tr>
                        <td class="px-6 py-4 text-sm">{{ $result->student->full_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $result->subject->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold">{{ $result->marks_obtained }}</td>
                        <td class="px-6 py-4 text-sm">{{ $result->max_marks }}</td>
                        <td class="px-6 py-4 text-sm">{{ $result->grade }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $result->isPassed() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $result->isPassed() ? 'Passed' : 'Failed' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="px-6 py-12 text-center text-gray-400">No results entered yet.</div>
            @endif
        </div>
    </div>
</div>
@endsection 
