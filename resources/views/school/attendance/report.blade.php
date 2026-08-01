@extends('layouts.admin')

@section('title', 'Attendance Report')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Attendance Report</h1>
            <a href="{{ route('school.attendance.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-arrow-left"></i> Mark Attendance
            </a>
        </div>

        <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs text-gray-500">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
                <i class="fa-solid fa-filter"></i> Generate
            </button>
        </form>

        @if($students->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Attendance Summary ({{ $startDate }} to {{ $endDate }})</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Roll</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student Name</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Present</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Absent</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Late</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Leave</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($students as $student)
                    <tr>
                        <td class="px-6 py-4 text-sm">{{ $student->roll_number ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $student->full_name }}</td>
                        <td class="px-6 py-4 text-sm text-center text-green-600 font-semibold">{{ $attendanceSummary[$student->id]['present'] ?? 0 }}</td>
                        <td class="px-6 py-4 text-sm text-center text-red-600 font-semibold">{{ $attendanceSummary[$student->id]['absent'] ?? 0 }}</td>
                        <td class="px-6 py-4 text-sm text-center text-yellow-600 font-semibold">{{ $attendanceSummary[$student->id]['late'] ?? 0 }}</td>
                        <td class="px-6 py-4 text-sm text-center text-blue-600 font-semibold">{{ $attendanceSummary[$student->id]['leave'] ?? 0 }}</td>
                        <td class="px-6 py-4 text-sm text-center font-semibold">{{ $attendanceSummary[$student->id]['total'] ?? 0 }}</td>
                        <td class="px-6 py-4 text-sm text-center font-bold">
                            @php
                                $total = $attendanceSummary[$student->id]['total'] ?? 0;
                                $present = $attendanceSummary[$student->id]['present'] ?? 0;
                                $percentage = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                            @endphp
                            <span class="{{ $percentage >= 75 ? 'text-green-600' : ($percentage >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $percentage }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif($classId && $sectionId)
            <div class="text-center py-12 text-gray-400">No students found in this class/section.</div>
        @else
            <div class="text-center py-12 text-gray-400">Select class and section to generate report.</div>
        @endif
    </div>
</div>
@endsection 
