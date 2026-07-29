<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School\Attendance;
use App\Models\School\Student;
use App\Models\School\Classes;
use App\Models\School\Section;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $classId = $request->class_id;
        $sectionId = $request->section_id;

        $classes = Classes::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->get();

        $students = collect();
        $attendance = collect();

        if ($classId && $sectionId) {
            $students = Student::where('organization_id', auth()->user()->organization_id)
                ->where('school_class_id', $classId)
                ->where('school_section_id', $sectionId)
                ->where('status', 'active')
                ->orderBy('roll_number')
                ->get();

            $attendance = Attendance::where('organization_id', auth()->user()->organization_id)
                ->where('date', $date)
                ->where('school_class_id', $classId)
                ->where('school_section_id', $sectionId)
                ->get()
                ->keyBy('school_student_id');
        }

        $sections = $classId ? Section::where('school_class_id', $classId)->get() : collect();

        return view('school.attendance.index', compact(
            'classes', 'sections', 'students', 'attendance', 'date', 'classId', 'sectionId'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:school_sections,id',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late,leave',
            'remarks' => 'nullable|array',
        ]);

        $orgId = auth()->user()->organization_id;

        foreach ($validated['attendance'] as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'organization_id' => $orgId,
                    'school_student_id' => $studentId,
                    'date' => $validated['date'],
                ],
                [
                    'school_class_id' => $validated['class_id'],
                    'school_section_id' => $validated['section_id'],
                    'status' => $status,
                    'remarks' => $request->remarks[$studentId] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance saved!');
    }

    public function report(Request $request)
    {
        $classId = $request->class_id;
        $sectionId = $request->section_id;
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $classes = Classes::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->get();

        $students = collect();
        $attendanceSummary = collect();

        if ($classId && $sectionId) {
            $students = Student::where('organization_id', auth()->user()->organization_id)
                ->where('school_class_id', $classId)
                ->where('school_section_id', $sectionId)
                ->where('status', 'active')
                ->orderBy('roll_number')
                ->get();

            foreach ($students as $student) {
                $attendances = Attendance::where('organization_id', auth()->user()->organization_id)
                    ->where('school_student_id', $student->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->get();

                $attendanceSummary[$student->id] = [
                    'present' => $attendances->where('status', 'present')->count(),
                    'absent' => $attendances->where('status', 'absent')->count(),
                    'late' => $attendances->where('status', 'late')->count(),
                    'leave' => $attendances->where('status', 'leave')->count(),
                    'total' => $attendances->count(),
                ];
            }
        }

        $sections = $classId ? Section::where('school_class_id', $classId)->get() : collect();

        return view('school.attendance.report', compact(
            'classes', 'sections', 'students', 'attendanceSummary', 'startDate', 'endDate', 'classId', 'sectionId'
        ));
    }
}