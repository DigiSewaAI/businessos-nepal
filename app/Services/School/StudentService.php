<?php

namespace App\Services\School;

use App\Models\School\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentService
{
    public function create(array $data)
    {
        $data['organization_id'] = auth()->user()->organization_id;
        $data['admission_number'] = $this->generateAdmissionNumber();

        return Student::create($data);
    }

    public function update(Student $student, array $data)
    {
        $student->update($data);
        return $student;
    }

    public function delete(Student $student)
    {
        if ($student->feeCollections()->exists()) {
            throw new \Exception('Cannot delete student with fee records.');
        }
        $student->delete();
    }

    public function generateAdmissionNumber()
    {
        $prefix = 'ADM-' . date('Y');
        $last = Student::where('organization_id', auth()->user()->organization_id)
            ->where('admission_number', 'LIKE', $prefix . '%')
            ->orderBy('admission_number', 'desc')
            ->first();

        if ($last) {
            $number = intval(substr($last->admission_number, -4)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getStudentsByClass($classId)
    {
        return Student::where('organization_id', auth()->user()->organization_id)
            ->where('school_class_id', $classId)
            ->where('status', 'active')
            ->orderBy('roll_number')
            ->get();
    }

    public function getStudentReport($studentId)
    {
        $student = Student::with('class', 'section')->findOrFail($studentId);

        $feeSummary = [
            'total' => $student->feeCollections()->sum('amount'),
            'paid' => $student->feeCollections()->where('status', 'paid')->sum('amount'),
            'due' => $student->feeCollections()->where('status', 'unpaid')->sum('amount'),
        ];

        $attendance = [
            'present' => $student->attendances()->where('status', 'present')->count(),
            'absent' => $student->attendances()->where('status', 'absent')->count(),
            'late' => $student->attendances()->where('status', 'late')->count(),
            'leave' => $student->attendances()->where('status', 'leave')->count(),
        ];

        $examResults = $student->examResults()->with('exam', 'subject')->get();

        return [
            'student' => $student,
            'fee_summary' => $feeSummary,
            'attendance' => $attendance,
            'exam_results' => $examResults,
        ];
    }
}