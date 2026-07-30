<?php
namespace App\Services\AI\Context;

use App\Models\School\Student;
use App\Models\School\Attendance;

class SchoolContext
{
    public function getData($orgId): array
    {
        $today = now()->toDateString();
        
        return [
            'type' => 'school',
            'total_students' => Student::where('organization_id', $orgId)->count(),
            'present_today' => Attendance::where('organization_id', $orgId)
                ->where('date', $today)
                ->where('status', 'present')
                ->count(),
            'absent_today' => Attendance::where('organization_id', $orgId)
                ->where('date', $today)
                ->where('status', 'absent')
                ->count(),
        ];
    }
}