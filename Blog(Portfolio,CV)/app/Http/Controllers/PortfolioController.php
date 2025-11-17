<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CVProfile;
use App\Models\Project;

class PortfolioController extends Controller
{
    /**
     * Show the welcome view with portfolio entries.
     */
    public function index()
    {
        $json = Storage::exists('portfolio.json') ? Storage::get('portfolio.json') : '[]';
        $entries = json_decode($json, true) ?: [];

        // Try to load the main CV profile (first profile) and show the
        // unified CV page which includes saved portfolio entries.
        $cv = CVProfile::with(['experiences', 'educations', 'skills', 'projects'])->first();

        if ($cv) {
            return view('cv.show', ['cv' => $cv, 'entries' => $entries]);
        }

        // If no CV exists yet, fall back to the simple welcome listing.
        return view('welcome', ['entries' => $entries]);
    }

    /**
     * Store a portfolio entry to projects table (new submissions).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
        ]);

        // Get or create default CV profile
        $cv = CVProfile::first();
        if (!$cv) {
            $cv = CVProfile::create([
                'full_name' => 'Default Portfolio',
                'title' => 'Community Portfolio',
            ]);
        }

        // Also save to JSON for backward compatibility
        $json = Storage::exists('portfolio.json') ? Storage::get('portfolio.json') : '[]';
        $entries = json_decode($json, true) ?: [];
        $data['created_at'] = now()->toDateTimeString();
        $entries[] = $data;
        Storage::put('portfolio.json', json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Save to projects table
        Project::create([
            'cv_profile_id' => $cv->id,
            'project_name' => $data['name'],
            'description' => $data['title'] ?? 'Community submission',
            'detailed_description' => $data['bio'] ?? '',
            'project_url' => $data['website'] ?? null,
            'github_url' => null,
            'start_date' => now()->toDateString(),
            'end_date' => null,
            'is_current' => false,
            'technologies' => [],
            'images' => [],
            'featured_image' => null,
            'submitted_by' => $data['name'],
            'is_approved' => true,
            'submission_type' => 'community',
            'submission_email' => $data['email'] ?? null,
            'submission_website' => $data['website'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Portfolio entry added.');
    }
}
