<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CVProfile;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    /**
     * Show create form
     */
    public function create()
    {
        $cv = CVProfile::firstOrFail();
        return view('admin.cv.experience-form', compact('cv'));
    }

    /**
     * Store new experience
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'employment_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'company_website' => 'nullable|url',
            'key_achievements' => 'nullable|string', // Comma-separated
        ]);

        if ($request->is_current) {
            $validated['end_date'] = null;
        }

        // Convert comma-separated achievements to array
        if (!empty($validated['key_achievements'])) {
            $validated['key_achievements'] = array_map(
                'trim',
                explode(',', $validated['key_achievements'])
            );
        }

        CVProfile::first()->experiences()->create($validated);

        return redirect()->route('admin.cv.experiences')
            ->with('success', 'Experience added successfully!');
    }

    /**
     * Show edit form
     */
    public function edit(Experience $experience)
    {
        $cv = CVProfile::firstOrFail();
        return view('admin.cv.experience-form', compact('cv', 'experience'));
    }

    /**
     * Update experience
     */
    public function update(Request $request, Experience $experience)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'employment_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'company_website' => 'nullable|url',
            'key_achievements' => 'nullable|string',
        ]);

        if ($request->is_current) {
            $validated['end_date'] = null;
        }

        if (!empty($validated['key_achievements'])) {
            $validated['key_achievements'] = array_map(
                'trim',
                explode(',', $validated['key_achievements'])
            );
        }

        $experience->update($validated);

        return redirect()->route('admin.cv.experiences')
            ->with('success', 'Experience updated successfully!');
    }

    /**
     * Delete experience
     */
    public function destroy(Experience $experience)
    {
        $experience->delete();

        return redirect()->route('admin.cv.experiences')
            ->with('success', 'Experience deleted successfully!');
    }
}
