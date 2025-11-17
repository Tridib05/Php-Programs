<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CVProfile;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    /**
     * Show create form
     */
    public function create()
    {
        $cv = CVProfile::firstOrFail();
        return view('admin.cv.education-form', compact('cv'));
    }

    /**
     * Store new education
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'degree' => 'required|string|max:100',
            'field_of_study' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'description' => 'nullable|string',
            'gpa' => 'nullable|numeric|between:0,4',
            'activities' => 'nullable|string', // Comma-separated
            'school_website' => 'nullable|url',
        ]);

        if ($request->is_current) {
            $validated['end_date'] = null;
        }

        if (!empty($validated['activities'])) {
            $validated['activities'] = array_map(
                'trim',
                explode(',', $validated['activities'])
            );
        }

        CVProfile::first()->educations()->create($validated);

        return redirect()->route('admin.cv.educations')
            ->with('success', 'Education added successfully!');
    }

    /**
     * Show edit form
     */
    public function edit(Education $education)
    {
        $cv = CVProfile::firstOrFail();
        return view('admin.cv.education-form', compact('cv', 'education'));
    }

    /**
     * Update education
     */
    public function update(Request $request, Education $education)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'degree' => 'required|string|max:100',
            'field_of_study' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'description' => 'nullable|string',
            'gpa' => 'nullable|numeric|between:0,4',
            'activities' => 'nullable|string',
            'school_website' => 'nullable|url',
        ]);

        if ($request->is_current) {
            $validated['end_date'] = null;
        }

        if (!empty($validated['activities'])) {
            $validated['activities'] = array_map(
                'trim',
                explode(',', $validated['activities'])
            );
        }

        $education->update($validated);

        return redirect()->route('admin.cv.educations')
            ->with('success', 'Education updated successfully!');
    }

    /**
     * Delete education
     */
    public function destroy(Education $education)
    {
        $education->delete();

        return redirect()->route('admin.cv.educations')
            ->with('success', 'Education deleted successfully!');
    }
}
