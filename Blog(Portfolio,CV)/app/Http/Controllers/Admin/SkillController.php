<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CVProfile;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Show create form
     */
    public function create()
    {
        $cv = CVProfile::firstOrFail();
        $categories = $cv->getSkillCategories();
        return view('admin.cv.skill-form', compact('cv', 'categories'));
    }

    /**
     * Store new skill
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'skill_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'proficiency' => 'required|integer|between:1,100',
            'description' => 'nullable|string|max:500',
            'icon_class' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        if (empty($validated['sort_order'])) {
            $validated['sort_order'] = CVProfile::first()->skills()->count() + 1;
        }

        CVProfile::first()->skills()->create($validated);

        return redirect()->route('admin.cv.skills')
            ->with('success', 'Skill added successfully!');
    }

    /**
     * Show edit form
     */
    public function edit(Skill $skill)
    {
        $cv = CVProfile::firstOrFail();
        $categories = $cv->getSkillCategories();
        return view('admin.cv.skill-form', compact('cv', 'skill', 'categories'));
    }

    /**
     * Update skill
     */
    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'skill_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'proficiency' => 'required|integer|between:1,100',
            'description' => 'nullable|string|max:500',
            'icon_class' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $skill->update($validated);

        return redirect()->route('admin.cv.skills')
            ->with('success', 'Skill updated successfully!');
    }

    /**
     * Delete skill
     */
    public function destroy(Skill $skill)
    {
        $skill->delete();

        return redirect()->route('admin.cv.skills')
            ->with('success', 'Skill deleted successfully!');
    }
}
