<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CVProfile;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Show create form
     */
    public function create()
    {
        $cv = CVProfile::firstOrFail();
        return view('admin.cv.project-form', compact('cv'));
    }

    /**
     * Store new project
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'detailed_description' => 'nullable|string',
            'project_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'technologies' => 'nullable|string', // Comma-separated
            'impact' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->is_current) {
            $validated['end_date'] = null;
        }

        if (!empty($validated['technologies'])) {
            $validated['technologies'] = array_map(
                'trim',
                explode(',', $validated['technologies'])
            );
        }

        if (empty($validated['sort_order'])) {
            $validated['sort_order'] = CVProfile::first()->projects()->count() + 1;
        }

        CVProfile::first()->projects()->create($validated);

        return redirect()->route('admin.cv.projects')
            ->with('success', 'Project added successfully!');
    }

    /**
     * Show edit form
     */
    public function edit(Project $project)
    {
        $cv = CVProfile::firstOrFail();
        return view('admin.cv.project-form', compact('cv', 'project'));
    }

    /**
     * Update project
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'detailed_description' => 'nullable|string',
            'project_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'technologies' => 'nullable|string',
            'impact' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->is_current) {
            $validated['end_date'] = null;
        }

        if (!empty($validated['technologies'])) {
            $validated['technologies'] = array_map(
                'trim',
                explode(',', $validated['technologies'])
            );
        }

        $project->update($validated);

        return redirect()->route('admin.cv.projects')
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Delete project
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.cv.projects')
            ->with('success', 'Project deleted successfully!');
    }
}
