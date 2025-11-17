<?php

namespace App\Http\Controllers;

use App\Models\CVProfile;
use Illuminate\Http\Request;

class CVController extends Controller
{
    /**
     * Display the public CV
     */
    public function show($id = 1)
    {
        $cv = CVProfile::with([
            'experiences' => fn($q) => $q->orderBy('start_date', 'desc'),
            'educations' => fn($q) => $q->orderBy('start_date', 'desc'),
            'skills' => fn($q) => $q->orderBy('category')->orderBy('sort_order'),
            'projects' => fn($q) => $q->orderBy('sort_order')
        ])->findOrFail($id);

        if (!$cv->is_public) {
            abort(403, 'This CV is not public');
        }

        return view('cv.show', compact('cv'));
    }

    /**
     * Display minimal CV for preview
     */
    public function preview()
    {
        $cv = CVProfile::with([
            'experiences' => fn($q) => $q->limit(3)->orderBy('start_date', 'desc'),
            'educations' => fn($q) => $q->limit(2)->orderBy('start_date', 'desc'),
            'skills' => fn($q) => $q->limit(10)->orderBy('sort_order'),
            'projects' => fn($q) => $q->limit(4)->orderBy('sort_order')
        ])->firstOrFail();

        return view('cv.preview', compact('cv'));
    }
}
