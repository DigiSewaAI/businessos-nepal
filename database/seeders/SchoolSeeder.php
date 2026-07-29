<?php

namespace Database\Seeders;

use App\Models\School\Classes;
use App\Models\School\Section;
use App\Models\School\Subject;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run()
    {
        $orgId = 2; // Your organization ID

        // Classes
        $classes = ['Nursery', 'LKG', 'UKG', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
        foreach ($classes as $index => $name) {
            Classes::create([
                'organization_id' => $orgId,
                'name' => $name,
                'code' => 'CLS-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Subjects
        $subjects = ['English', 'Nepali', 'Mathematics', 'Science', 'Social Studies', 'Computer', 'Accountancy', 'Economics'];
        foreach ($subjects as $subject) {
            Subject::create([
                'organization_id' => $orgId,
                'name' => $subject,
                'code' => 'SUB-' . strtoupper(substr($subject, 0, 3)),
                'is_active' => true,
            ]);
        }

        // Sections for Class 1-10
        $classes = Classes::where('organization_id', $orgId)->get();
        foreach ($classes as $class) {
            if (in_array($class->name, ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'])) {
                Section::create([
                    'organization_id' => $orgId,
                    'school_class_id' => $class->id,
                    'name' => 'A',
                    'capacity' => 30,
                    'is_active' => true,
                ]);
                Section::create([
                    'organization_id' => $orgId,
                    'school_class_id' => $class->id,
                    'name' => 'B',
                    'capacity' => 30,
                    'is_active' => true,
                ]);
            } else {
                Section::create([
                    'organization_id' => $orgId,
                    'school_class_id' => $class->id,
                    'name' => 'A',
                    'capacity' => 25,
                    'is_active' => true,
                ]);
            }
        }
    }
}