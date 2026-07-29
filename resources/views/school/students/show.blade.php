@extends('layouts.app')

@section('title', $student->full_name)

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $student->full_name }}</h1>
                    <p class="text-sm text-gray-500">{{ $student->admission_number }} | {{ $student->class->name }} - {{ $student->section->name }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $student->status == 'active' ? 'bg-green-100 text-green-700' : '' }}">
                    {{ ucfirst($student->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-6">
                <div>
                    <p class="text-xs text-gray-500">Guardian</p>
                    <p class="font-medium">{{ $student->guardian_name }}</p>
                    <p class="text-sm text-gray-500">{{ $student->guardian_phone }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Contact</p>
                    <p class="font-medium">{{ $student->phone }}</p>
                    <p class="text-sm text-gray-500">{{ $student->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Date of Birth</p>
                    <p class="font-medium">{{ $student->date_of_birth->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Admission Date</p>
                    <p class="font-medium">{{ $student->admission_date->format('d M Y') }}</p>
                </div>
            </div>

            <!-- Fee Summary -->
            <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                <h3 class="font-semibold text-gray-800">Fee Summary</h3>
                <div class="grid grid-cols-3 gap-4 mt-2">
                    <div><span class="text-xs text-gray-500">Total</span> <span class="font-bold">Rs. {{ number_format($fee_summary['total'], 2) }}</span></div>
                    <div><span class="text-xs text-gray-500">Paid</span> <span class="font-bold text-green-600">Rs. {{ number_format($fee_summary['paid'], 2) }}</span></div>
                    <div><span class="text-xs text-gray-500">Due</span> <span class="font-bold text-red-600">Rs. {{ number_format($fee_summary['due'], 2) }}</span></div>
                </div>
            </div>

            <!-- Attendance Summary -->
            <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                <h3 class="font-semibold text-gray-800">Attendance</h3>
                <div class="grid grid-cols-4 gap-4 mt-2">
                    <div><span class="text-xs text-gray-500">Present</span> <span class="font-bold text-green-600">{{ $attendance['present'] }}</span></div>
                    <div><span class="text-xs text-gray-500">Absent</span> <span class="font-bold text-red-600">{{ $attendance['absent'] }}</span></div>
                    <div><span class="text-xs text-gray-500">Late</span> <span class="font-bold text-yellow-600">{{ $attendance['late'] }}</span></div>
                    <div><span class="text-xs text-gray-500">Leave</span> <span class="font-bold text-blue-600">{{ $attendance['leave'] }}</span></div>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('school.students.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 
