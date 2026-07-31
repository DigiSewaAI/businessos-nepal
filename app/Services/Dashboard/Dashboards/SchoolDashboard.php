<?php

namespace App\Services\Dashboard\Dashboards;

use App\Services\Dashboard\BaseDashboard;
use App\Models\School\Student;
use App\Models\School\Attendance;
use App\Models\School\FeeCollection;
use App\Models\School\Teacher;
use Illuminate\Support\Facades\DB;

class SchoolDashboard extends BaseDashboard
{
    public function getData(): array
    {
        $today = now()->toDateString();

        // 1. Total Students
        $totalStudents = Student::where('organization_id', $this->organizationId)->count();

        // 2. Today's Attendance
        $present = Attendance::where('organization_id', $this->organizationId)
            ->where('date', $today)
            ->where('status', 'present')
            ->count();

        $absent = Attendance::where('organization_id', $this->organizationId)
            ->where('date', $today)
            ->where('status', 'absent')
            ->count();

        $attendanceRate = $totalStudents > 0 ? round(($present / $totalStudents) * 100, 1) : 0;

        // 3. Pending Fees
        $pendingFees = FeeCollection::where('organization_id', $this->organizationId)
            ->whereIn('status', ['unpaid', 'partial'])
            ->sum('amount');

        $totalFees = FeeCollection::where('organization_id', $this->organizationId)->sum('amount');
        $paidFees = FeeCollection::where('organization_id', $this->organizationId)
            ->where('status', 'paid')
            ->sum('paid_amount');

        // 4. Total Teachers
        $totalTeachers = Teacher::where('organization_id', $this->organizationId)->count();

        // 5. Recent Attendance (last 5)
        $recentAttendance = Attendance::where('organization_id', $this->organizationId)
            ->with('student')
            ->where('date', $today)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 6. Monthly Fee Collection
        $monthlyFees = FeeCollection::where('organization_id', $this->organizationId)
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return [
            'industry' => $this->industry,
            'business_category' => $this->businessCategory,
            'totalStudents' => $totalStudents,
            'present' => $present,
            'absent' => $absent,
            'attendanceRate' => $attendanceRate,
            'pendingFees' => $pendingFees,
            'totalFees' => $totalFees,
            'paidFees' => $paidFees,
            'totalTeachers' => $totalTeachers,
            'recentAttendance' => $recentAttendance,
            'monthlyFees' => $monthlyFees,
        ];
    }

    public function getBusinessCategories(): array
    {
        return ['nursery', 'primary', 'secondary', 'college', 'training'];
    }
}