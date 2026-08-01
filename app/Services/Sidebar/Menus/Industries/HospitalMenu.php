<?php

namespace App\Services\Sidebar\Menus\Industries;

class HospitalMenu
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
                'label' => 'Patients',
                'icon' => 'fa-user-injured',
                'route' => 'hospital.patients.index',
                'active' => 'hospital.patients.*',
                'permission' => null,
                'badge' => 'New',
            ],
            [
                'label' => 'Appointments',
                'icon' => 'fa-calendar-check',
                'route' => 'hospital.appointments.index',
                'active' => 'hospital.appointments.*',
                'permission' => null,
                'badge' => 'New',
            ],
            [
                'label' => 'Doctors',
                'icon' => 'fa-user-md',
                'route' => 'hospital.doctors.index',
                'active' => 'hospital.doctors.*',
                'permission' => null,
            ],
            [
                'label' => 'Prescriptions',
                'icon' => 'fa-prescription-bottle',
                'route' => 'hospital.prescriptions.index',
                'active' => 'hospital.prescriptions.*',
                'permission' => null,
                'badge' => 'New',
            ],
            [
                'label' => 'Billing',
                'icon' => 'fa-file-invoice-dollar',
                'route' => 'hospital.billing.index',
                'active' => 'hospital.billing.*',
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