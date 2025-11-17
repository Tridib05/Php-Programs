<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\CVProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PortfolioModerationController extends Controller
{
    public function index(Request $request)
    {
        $cvId = $request->get('cv_id', 1);
        $projects = Project::where('cv_profile_id', $cvId)
            ->where('submission_type', 'community')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $cv = CVProfile::find($cvId);
        return view('admin.cv.portfolio-moderation', compact('projects', 'cv', 'cvId'));
    }

    public function approve(Project $project)
    {
        if ($project->submission_type !== 'community') {
            return redirect()->back()->with('error', 'Only community entries can be approved.');
        }
        $project->update(['is_approved' => true]);
        return redirect()->back()->with('success', "'{$project->project_name}' approved.");
    }

    public function reject(Project $project)
    {
        if ($project->submission_type !== 'community') {
            return redirect()->back()->with('error', 'Only community entries can be rejected.');
        }
        $project->update(['is_approved' => false]);
        return redirect()->back()->with('success', "'{$project->project_name}' rejected.");
    }

    public function delete(Project $project)
    {
        $name = $project->project_name;
        if ($project->submission_type !== 'community') {
            return redirect()->back()->with('error', 'Only community entries can be deleted.');
        }
        $project->delete();
        return redirect()->back()->with('success', "'{$name}' deleted.");
    }

    public function stats(Request $request)
    {
        $cvId = $request->get('cv_id', 1);
        $cv = CVProfile::find($cvId);
        $total = Project::where('cv_profile_id', $cvId)
            ->where('submission_type', 'community')
            ->count();
        $approved = Project::where('cv_profile_id', $cvId)
            ->where('submission_type', 'community')
            ->where('is_approved', true)
            ->count();
        $pending = Project::where('cv_profile_id', $cvId)
            ->where('submission_type', 'community')
            ->where('is_approved', false)
            ->count();
        $direct = Project::where('cv_profile_id', $cvId)
            ->where('submission_type', 'direct')
            ->count();
        return view('admin.cv.portfolio-stats', compact('cv', 'cvId', 'total', 'approved', 'pending', 'direct'));
    }
}
