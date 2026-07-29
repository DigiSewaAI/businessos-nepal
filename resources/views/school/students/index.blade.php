@extends('layouts.app')

@section('title', 'Students')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Students</h1>
            <a href="{{ route('school.students.create') }}" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-plus"></i> Admit Student
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Admission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guardian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($students as $student)
                    <tr>
                        <td class="px-6 py-4 text-sm font-mono text-gray-800">{{ $student->admission_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $student->full_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $student->class->name }} - {{ $student->section->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $student->guardian_name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $student->status == 'active' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $student->status == 'inactive' ? 'bg-gray-100 text-gray-700' : '' }}
                                {{ $student->status == 'graduated' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $student->status == 'transferred' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('school.students.show', $student) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('school.students.edit', $student) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <form action="{{ route('school.students.destroy', $student) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Delete this student?')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 
