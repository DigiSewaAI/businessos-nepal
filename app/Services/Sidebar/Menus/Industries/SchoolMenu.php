<?php

namespace App\Services\Sidebar\Menus\Industries;

class SchoolMenu
{
    public function getItems($user): array
    {
        return [
            [
                'label' => 'Dashboard',
                'icon' => 'fa-gauge-high',
                'route' => 'dashboard',
                'active' => 'dashboard',
                'permission' => null,
            ],
            [
                'label' => 'Students',
                'icon' => 'fa-users',
                'route' => 'school.students.index',
                'active' => 'school.students.*',
                'permission' => null,
            ],
            [
                'label' => 'Teachers',      // ✅ यो थप्नुहोस्
                'icon' => 'fa-chalkboard-user',
                'route' => 'school.teachers.index',
                'active' => 'school.teachers.*',
                'permission' => null,
            ],
            [
                'label' => 'Attendance',
                'icon' => 'fa-clipboard-check',
                'route' => 'school.attendance.index',
                'active' => 'school.attendance.*',
                'permission' => null,
            ],
            [
                'label' => 'Fees',
                'icon' => 'fa-money-bill-wave',
                'route' => 'school.fees.index',
                'active' => 'school.fees.*',
                'permission' => null,
            ],
            [
                'label' => 'Exams',
                'icon' => 'fa-pen-to-square',
                'route' => 'school.exams.index',
                'active' => 'school.exams.*',
                'permission' => null,
            ],
            [
                'label' => 'AI Assistant',
                'icon' => 'fa-robot',
                'route' => 'ai.chat',
                'active' => 'ai.*',
                'permission' => null,
                'badge' => 'New',
            ],
        ];
    }
}