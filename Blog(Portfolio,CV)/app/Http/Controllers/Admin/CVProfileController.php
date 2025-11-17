<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CVProfile;
use Illuminate\Http\Request;

class CVProfileController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function dashboard()
    {
        $cv = CVProfile::first() ?? new CVProfile();
        
        $stats = [
            'experiences' => $cv->experiences()->count(),
            'educations' => $cv->educations()->count(),
            'skills' => $cv->skills()->count(),
            'projects' => $cv->projects()->count(),
        ];

        return view('admin.cv.dashboard', compact('cv', 'stats'));
    }

    /**
     * Edit profile information
     */
    public function editProfile()
    {
        $cv = CVProfile::firstOrCreate(
            [],
            ['full_name' => 'Your Name']
        );

        return view('admin.cv.edit-profile', compact('cv'));
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'email' => 'required|email|unique:cv_profiles,email,' . ($request->cv_id ?? 'NULL'),
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'website_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'portfolio_url' => 'nullable|url',
            'about_me' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $cv = CVProfile::firstOrCreate([], $validated);
        $cv->update($validated);

        return redirect()->route('admin.cv.dashboard')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show all experiences
     */
    public function experiences()
    {
        $cv = CVProfile::first();
        $experiences = $cv->experiences()->orderBy('start_date', 'desc')->paginate(10);

        return view('admin.cv.experiences', compact('cv', 'experiences'));
    }

    /**
     * Show all educations
     */
    public function educations()
    {
        $cv = CVProfile::first();
        $educations = $cv->educations()->orderBy('start_date', 'desc')->paginate(10);

        return view('admin.cv.educations', compact('cv', 'educations'));
    }

    /**
     * Show all skills
     */
    public function skills()
    {
        $cv = CVProfile::first();
        $skills = $cv->skills()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->paginate(15);

        $categories = $cv->getSkillCategories();

        return view('admin.cv.skills', compact('cv', 'skills', 'categories'));
    }

    /**
     * Show all projects
     */
    public function projects()
    {
        $cv = CVProfile::first();
        $projects = $cv->projects()->orderBy('sort_order')->paginate(10);

        return view('admin.cv.projects', compact('cv', 'projects'));
    }
}
