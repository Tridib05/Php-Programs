<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CVController;
use App\Http\Controllers\Admin\CVProfileController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\PortfolioModerationController;

// Public CV Routes
Route::get('/cv', [CVController::class, 'show'])->name('cv.show');
Route::get('/cv-preview', [CVController::class, 'preview'])->name('cv.preview');

// Admin Routes
Route::prefix('admin/cv')->middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [CVProfileController::class, 'dashboard'])->name('admin.cv.dashboard');
    Route::get('/profile/edit', [CVProfileController::class, 'editProfile'])->name('admin.cv.edit-profile');
    Route::put('/profile/update', [CVProfileController::class, 'updateProfile'])->name('admin.cv.update-profile');

    // Experiences
    Route::prefix('experiences')->group(function () {
        Route::get('/', [CVProfileController::class, 'experiences'])->name('admin.cv.experiences');
        Route::get('/create', [ExperienceController::class, 'create'])->name('admin.cv.experience-create');
        Route::post('/', [ExperienceController::class, 'store'])->name('admin.cv.experience-store');
        Route::get('/{experience}/edit', [ExperienceController::class, 'edit'])->name('admin.cv.experience-edit');
        Route::put('/{experience}', [ExperienceController::class, 'update'])->name('admin.cv.experience-update');
        Route::delete('/{experience}', [ExperienceController::class, 'destroy'])->name('admin.cv.experience-destroy');
    });

    // Educations
    Route::prefix('educations')->group(function () {
        Route::get('/', [CVProfileController::class, 'educations'])->name('admin.cv.educations');
        Route::get('/create', [EducationController::class, 'create'])->name('admin.cv.education-create');
        Route::post('/', [EducationController::class, 'store'])->name('admin.cv.education-store');
        Route::get('/{education}/edit', [EducationController::class, 'edit'])->name('admin.cv.education-edit');
        Route::put('/{education}', [EducationController::class, 'update'])->name('admin.cv.education-update');
        Route::delete('/{education}', [EducationController::class, 'destroy'])->name('admin.cv.education-destroy');
    });

    // Skills
    Route::prefix('skills')->group(function () {
        Route::get('/', [CVProfileController::class, 'skills'])->name('admin.cv.skills');
        Route::get('/create', [SkillController::class, 'create'])->name('admin.cv.skill-create');
        Route::post('/', [SkillController::class, 'store'])->name('admin.cv.skill-store');
        Route::get('/{skill}/edit', [SkillController::class, 'edit'])->name('admin.cv.skill-edit');
        Route::put('/{skill}', [SkillController::class, 'update'])->name('admin.cv.skill-update');
        Route::delete('/{skill}', [SkillController::class, 'destroy'])->name('admin.cv.skill-destroy');
    });

    // Projects
    Route::prefix('projects')->group(function () {
        Route::get('/', [CVProfileController::class, 'projects'])->name('admin.cv.projects');
        Route::get('/create', [ProjectController::class, 'create'])->name('admin.cv.project-create');
        Route::post('/', [ProjectController::class, 'store'])->name('admin.cv.project-store');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('admin.cv.project-edit');
        Route::put('/{project}', [ProjectController::class, 'update'])->name('admin.cv.project-update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('admin.cv.project-destroy');
    });

    // Portfolio Moderation (for community submissions)
    Route::prefix('portfolio')->group(function () {
        Route::get('/', [PortfolioModerationController::class, 'index'])->name('admin.cv.portfolio-moderation');
        Route::get('/stats', [PortfolioModerationController::class, 'stats'])->name('admin.cv.portfolio-stats');
        Route::post('/{project}/approve', [PortfolioModerationController::class, 'approve'])->name('admin.cv.portfolio-approve');
        Route::post('/{project}/reject', [PortfolioModerationController::class, 'reject'])->name('admin.cv.portfolio-reject');
        Route::delete('/{project}', [PortfolioModerationController::class, 'delete'])->name('admin.cv.portfolio-delete');
    });
});
