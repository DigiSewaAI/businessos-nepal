@extends('layouts.admin')

@section('title', 'School Dashboard')

@section('content')
<div class="pt-4 pb-8 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Welcome, {{ Auth::user()->name }}! 🎓
                </h1>
                <p class="text-sm text-gray-500">
                    {{ Auth::user()->organization->name }} | 
                    {{ Auth::user()->branch->name }} | 
                    Role: <span class="font-medium text-gray-700">{{ Auth::user()->roles->first()->name ?? 'No Role' }}</span>
                </p>
            </div>
            <span class="text-sm text-gray-400">{{ now()->format('d M Y, h:i A') }}</span>
        </div>

        <!-- Stats Grid (School Specific) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Students</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalStudents ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Today's Attendance</p>
                        <p class="text-2xl font-bold {{ ($attendanceRate ?? 0) >= 75 ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $attendanceRate ?? 0 }}%
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Pending Fees</p>
                        <p class="text-2xl font-bold text-red-600">Rs. {{ number_format($pendingFees ?? 0, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Teachers</p>
                        <p class="text-2xl font-bold text-teal-600">{{ $totalTeachers ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Assistant Widget -->
        <div class="mb-8 bg-gradient-to-r from-blue-50 via-white to-teal-50 rounded-2xl border border-blue-100 p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-teal-500 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg">
                        <i class="fa-regular fa-comment-dots"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">🤖 AI Assistant</h3>
                        <p class="text-sm text-gray-500">Ask about students, attendance, fees, or get instant insights.</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('ai.chat') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-md hover:shadow-lg flex items-center gap-2">
                        <i class="fa-regular fa-paper-plane"></i> Ask AI
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Attendance + Monthly Fees -->
        <div class="grid lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-list text-blue-500"></i> Today's Attendance
                </h3>
                @if(isset($recentAttendance) && count($recentAttendance) > 0)
                    <div class="space-y-3">
                        @foreach($recentAttendance as $attendance)
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-sm text-gray-700">{{ $attendance->student->full_name ?? 'N/A' }}</span>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $attendance->status == 'present' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 text-center py-6">
                        <i class="fa-regular fa-face-frown text-2xl block mb-2"></i>
                        No attendance recorded today
                    </p>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-chart-bar text-blue-500"></i> Monthly Fee Collection ({{ now()->year }})
                </h3>
                @if(isset($monthlyFees) && count($monthlyFees) > 0)
                    @php
                        $maxValue = $monthlyFees->max() ?: 1;
                    @endphp
                    <div class="flex items-end h-48 space-x-2">
                        @foreach(range(1, 12) as $month)
                            @php
                                $value = $monthlyFees[$month] ?? 0;
                                $height = $value > 0 ? max(($value / $maxValue) * 100, 5) : 5;
                            @endphp
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-full bg-blue-500/20 rounded-t" style="height: {{ $height }}%; min-height: 5px;"></div>
                                <span class="text-xs text-gray-500 mt-1">{{ date('M', mktime(0,0,0,$month,1)) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 text-center py-6">
                        <i class="fa-regular fa-calendar text-2xl block mb-2"></i>
                        No fee data yet
                    </p>
                @endif
            </div>
        </div>

        <!-- Quick Actions (School Specific) -->
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-bolt text-yellow-500"></i> Quick Actions
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <!-- Admit Student -->
                <a href="{{ route('school.students.create') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-blue-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Admit Student</span>
                </a>

                <!-- ✅ NEW: View Teachers -->
                <a href="{{ route('school.teachers.index') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-purple-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">View Teachers</span>
                </a>

                <!-- Mark Attendance -->
                <a href="{{ route('school.attendance.index') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-emerald-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Mark Attendance</span>
                </a>

                <!-- Generate Fee (✅ Fixed route) -->
                <a href="{{ route('school.fees.generate', ['student' => 0]) }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-amber-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Generate Fee</span>
                </a>

                <!-- Manage Exams -->
                <a href="{{ route('school.exams.index') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-purple-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Manage Exams</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
