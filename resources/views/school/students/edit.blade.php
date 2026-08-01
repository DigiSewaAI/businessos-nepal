@extends('layouts.admin')

@section('title', 'Edit Student')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('school.students.show', $student) }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Student: {{ $student->full_name }}</h1>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('school.students.update', $student) }}" method="POST">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-500 @enderror"
                            required>
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-500 @enderror"
                            required>
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('date_of_birth') border-red-500 @enderror"
                            required>
                        @error('date_of_birth') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('gender') border-red-500 @enderror" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Class -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Class <span class="text-red-500">*</span></label>
                        <select name="school_class_id" id="class_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('school_class_id') border-red-500 @enderror" required onchange="loadSections()">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('school_class_id', $student->school_class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_class_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Section <span class="text-red-500">*</span></label>
                        <select name="school_section_id" id="section_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('school_section_id') border-red-500 @enderror" required>
                            <option value="">Select Class First</option>
                            @if(isset($sections))
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ old('school_section_id', $student->school_section_id) == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('school_section_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $student->email) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror" rows="2">{{ old('address', $student->address) }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Guardian Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Guardian Name <span class="text-red-500">*</span></label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('guardian_name') border-red-500 @enderror"
                            required>
                        @error('guardian_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Guardian Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Guardian Phone <span class="text-red-500">*</span></label>
                        <input type="text" name="guardian_phone" value="{{ old('guardian_phone', $student->guardian_phone) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('guardian_phone') border-red-500 @enderror"
                            required>
                        @error('guardian_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Guardian Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Guardian Email</label>
                        <input type="email" name="guardian_email" value="{{ old('guardian_email', $student->guardian_email) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('guardian_email') border-red-500 @enderror">
                        @error('guardian_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Graduated</option>
                            <option value="transferred" {{ old('status', $student->status) == 'transferred' ? 'selected' : '' }}>Transferred</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3 border-t border-gray-200 pt-6">
                    <a href="{{ route('school.students.show', $student) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                        <i class="fa-solid fa-save"></i> Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function loadSections() {
        const classId = document.getElementById('class_id').value;
        const sectionSelect = document.getElementById('section_id');
        const currentSection = "{{ old('school_section_id', $student->school_section_id) }}";

        if (!classId) {
            sectionSelect.innerHTML = '<option value="">Select Class First</option>';
            return;
        }

        // Fetch sections via AJAX
        fetch(`/api/sections?class_id=${classId}`)
            .then(response => response.json())
            .then(data => {
                sectionSelect.innerHTML = '<option value="">Select Section</option>';
                data.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.name;
                    if (section.id == currentSection) {
                        option.selected = true;
                    }
                    sectionSelect.appendChild(option);
                });
            })
            .catch(() => {
                sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
            });
    }

    // Load sections on page load
    document.addEventListener('DOMContentLoaded', function() {
        const classId = document.getElementById('class_id').value;
        if (classId) {
            loadSections();
        }
    });
</script>
@endsection 
