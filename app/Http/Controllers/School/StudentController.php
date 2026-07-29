<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School\Student;
use App\Models\School\Classes;
use App\Models\School\Section;
use App\Services\School\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index()
    {
        $students = Student::with('class', 'section')
            ->where('organization_id', auth()->user()->organization_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('school.students.index', compact('students'));
    }

    public function create()
    {
        $classes = Classes::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->get();

        return view('school.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'school_section_id' => 'required|exists:school_sections,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'admission_date' => 'required|date',
        ]);

        try {
            $student = $this->studentService->create($validated);

            // Generate fee invoices
            app(FeeService::class)->generateInvoices($student->id);

            return redirect()->route('school.students.show', $student)->with('success', 'Student admitted!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Student $student)
    {
        $report = $this->studentService->getStudentReport($student->id);
        return view('school.students.show', $report);
    }

    public function edit(Student $student)
    {
        $classes = Classes::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->get();

        return view('school.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'school_section_id' => 'required|exists:school_sections,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
        ]);

        $this->studentService->update($student, $validated);
        return redirect()->route('school.students.show', $student)->with('success', 'Student updated.');
    }

    public function destroy(Student $student)
    {
        $this->studentService->delete($student);
        return redirect()->route('school.students.index')->with('success', 'Student deleted.');
    }
}