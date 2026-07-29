<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School\Exam;
use App\Models\School\ExamResult;
use App\Models\School\Student;
use App\Models\School\Subject;
use App\Models\School\Classes;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('class')
            ->where('organization_id', auth()->user()->organization_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('school.exams.index', compact('exams'));
    }

    public function create()
    {
        $classes = Classes::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->get();

        return view('school.exams.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|max:' . $request->max_marks,
            'description' => 'nullable|string',
            'status' => 'nullable|in:upcoming,ongoing,completed',
        ]);

        $validated['organization_id'] = auth()->user()->organization_id;

        $exam = Exam::create($validated);

        return redirect()->route('school.exams.index')->with('success', 'Exam created!');
    }

    public function show(Exam $exam)
    {
        $exam->load('class', 'results.student', 'results.subject');
        return view('school.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        $classes = Classes::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->get();

        return view('school.exams.edit', compact('exam', 'classes'));
    }

    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|max:' . $request->max_marks,
            'description' => 'nullable|string',
            'status' => 'nullable|in:upcoming,ongoing,completed',
        ]);

        $exam->update($validated);

        return redirect()->route('school.exams.index')->with('success', 'Exam updated!');
    }

    public function destroy(Exam $exam)
    {
        if ($exam->results()->exists()) {
            return back()->with('error', 'Cannot delete exam with results.');
        }
        $exam->delete();
        return redirect()->route('school.exams.index')->with('success', 'Exam deleted.');
    }

    public function saveResults(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'results' => 'required|array',
            'results.*.student_id' => 'required|exists:school_students,id',
            'results.*.subject_id' => 'required|exists:school_subjects,id',
            'results.*.marks_obtained' => 'required|numeric|min:0|max:' . $exam->max_marks,
            'results.*.remarks' => 'nullable|string',
        ]);

        $orgId = auth()->user()->organization_id;

        foreach ($validated['results'] as $result) {
            ExamResult::updateOrCreate(
                [
                    'organization_id' => $orgId,
                    'school_exam_id' => $exam->id,
                    'school_student_id' => $result['student_id'],
                    'school_subject_id' => $result['subject_id'],
                ],
                [
                    'marks_obtained' => $result['marks_obtained'],
                    'max_marks' => $exam->max_marks,
                    'remarks' => $result['remarks'] ?? null,
                ]
            );
        }

        return redirect()->route('school.exams.results.view', $exam)->with('success', 'Results saved!');
    }

    public function viewResults(Exam $exam)
    {
        $exam->load('class', 'results.student', 'results.subject');

        $students = Student::where('organization_id', auth()->user()->organization_id)
            ->where('school_class_id', $exam->school_class_id)
            ->where('status', 'active')
            ->orderBy('roll_number')
            ->get();

        $subjects = Subject::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->get();

        $results = $exam->results->groupBy('school_student_id');

        return view('school.exams.results', compact('exam', 'students', 'subjects', 'results'));
    }
}