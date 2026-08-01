<?php
namespace App\Services\AI\Context;

use App\Models\School\Student;
use App\Models\School\Attendance;
use App\Models\School\Teacher;
use App\Models\School\FeeCollection;
use App\Models\School\Exam;

class SchoolContext
{
    public function getData($orgId): array
    {
        $today = now()->toDateString();
        
        return [
            'total_students' => Student::where('organization_id', $orgId)->count(),
            'total_teachers' => Teacher::where('organization_id', $orgId)->count(),
            'present_today' => Attendance::where('organization_id', $orgId)
                ->where('date', $today)
                ->where('status', 'present')
                ->count(),
            'absent_today' => Attendance::where('organization_id', $orgId)
                ->where('date', $today)
                ->where('status', 'absent')
                ->count(),
            'pending_fees' => FeeCollection::where('organization_id', $orgId)
                ->where('status', 'unpaid')
                ->sum('amount'),
            'upcoming_exams' => Exam::where('organization_id', $orgId)
                ->where('exam_date', '>=', $today)
                ->count(),
        ];
    }
}