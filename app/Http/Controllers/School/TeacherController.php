<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     */
    public function index()
    {
        $organizationId = auth()->user()->organization_id;

        $teachers = Teacher::where('organization_id', $organizationId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('school.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        return view('school.teachers.create');
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:school_teachers,email',
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string',
            'date_of_birth'=> 'nullable|date',
            'gender'       => 'nullable|in:male,female,other',
            'qualification'=> 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'status'       => 'nullable|in:active,inactive',
        ]);

        $validated['organization_id'] = auth()->user()->organization_id;

        Teacher::create($validated);

        return redirect()
            ->route('school.teachers.index')
            ->with('success', 'Teacher added successfully.');
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher)
    {
        // Ensure teacher belongs to the current organization
        if ($teacher->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('school.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(Teacher $teacher)
    {
        if ($teacher->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('school.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        if ($teacher->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:school_teachers,email,' . $teacher->id,
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string',
            'date_of_birth'=> 'nullable|date',
            'gender'       => 'nullable|in:male,female,other',
            'qualification'=> 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'status'       => 'nullable|in:active,inactive',
        ]);

        $teacher->update($validated);

        return redirect()
            ->route('school.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified teacher from storage.
     */
    public function destroy(Teacher $teacher)
    {
        if ($teacher->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Unauthorized action.');
        }

        $teacher->delete();

        return redirect()
            ->route('school.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}