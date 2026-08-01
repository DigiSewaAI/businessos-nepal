@extends('layouts.admin')

@section('title', 'Mark Attendance')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Mark Attendance</h1>
            <a href="{{ route('school.attendance.report') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-chart-bar"></i> Report
            </a>
        </div>

        <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs text-gray-500">Date</label>
                <input type="date" name="date" value="{{ $date }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500">Class</label>
                <select name="class_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm" onchange="this.form.submit()">
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Section</label>
                <select name="section_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm" onchange="this.form.submit()">
                    <option value="">Select Section</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" {{ $sectionId == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-filter"></i> Load
            </button>
        </form>

        @if($students->count() > 0)
        <form method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            <input type="hidden" name="class_id" value="{{ $classId }}">
            <input type="hidden" name="section_id" value="{{ $sectionId }}">

            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Roll</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($students as $student)
                    <tr>
                        <td class="px-6 py-4 text-sm">{{ $student->roll_number ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $student->full_name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <select name="attendance[{{ $student->id }}]" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                                <option value="present" {{ isset($attendance[$student->id]) && $attendance[$student->id]->status == 'present' ? 'selected' : '' }}>Present</option>
                                <option value="absent" {{ isset($attendance[$student->id]) && $attendance[$student->id]->status == 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="late" {{ isset($attendance[$student->id]) && $attendance[$student->id]->status == 'late' ? 'selected' : '' }}>Late</option>
                                <option value="leave" {{ isset($attendance[$student->id]) && $attendance[$student->id]->status == 'leave' ? 'selected' : '' }}>Leave</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <input type="text" name="remarks[{{ $student->id }}]" value="{{ $attendance[$student->id]->remarks ?? '' }}"
                                class="w-full px-3 py-1 border border-gray-300 rounded-lg text-sm" placeholder="Optional">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fa-solid fa-save"></i> Save Attendance
                </button>
            </div>
        </form>
        @elseif($classId && $sectionId)
            <div class="text-center py-12 text-gray-400">No students found in this class/section.</div>
        @else
            <div class="text-center py-12 text-gray-400">Select class and section to mark attendance.</div>
        @endif
    </div>
</div>
@endsection 
