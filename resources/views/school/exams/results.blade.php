@extends('layouts.admin')

@section('title', 'Enter Results - ' . $exam->name)

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('school.exams.show', $exam) }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Enter Results: {{ $exam->name }}</h1>
        </div>

        <form method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            @csrf

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            @foreach($subjects as $subject)
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ $subject->name }}</th>
                            @endforeach
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($students as $student)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium">{{ $student->full_name }}</td>
                            @foreach($subjects as $subject)
                                @php
                                    $result = $results[$student->id]->where('school_subject_id', $subject->id)->first() ?? null;
                                    $marks = $result ? $result->marks_obtained : '';
                                @endphp
                                <td class="px-4 py-3 text-center">
                                    <input type="number" step="0.01" 
                                        name="results[{{ $loop->parent->index }}][{{ $loop->index }}][marks]" 
                                        value="{{ $marks }}"
                                        class="w-20 px-2 py-1 border border-gray-300 rounded-lg text-sm text-center"
                                        min="0" max="{{ $exam->max_marks }}"
                                        data-student="{{ $student->id }}" 
                                        data-subject="{{ $subject->id }}">
                                    <input type="hidden" name="results[{{ $loop->parent->index }}][{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                                    <input type="hidden" name="results[{{ $loop->parent->index }}][{{ $loop->index }}][subject_id]" value="{{ $subject->id }}">
                                </td>
                            @endforeach
                            <td class="px-4 py-3 text-center font-bold" id="total-{{ $student->id }}">
                                {{ $results[$student->id]->sum('marks_obtained') ?? 0 }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                    <i class="fa-solid fa-save"></i> Save Results
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('change', function() {
            const studentId = this.dataset.student;
            const row = this.closest('tr');
            const inputs = row.querySelectorAll('input[type="number"]');
            let total = 0;
            inputs.forEach(inp => {
                total += parseFloat(inp.value) || 0;
            });
            document.getElementById('total-' + studentId).textContent = total.toFixed(2);
        });
    });
</script>
@endsection 
