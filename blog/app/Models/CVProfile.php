<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CVProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'title',
        'bio',
        'email',
        'phone',
        'location',
        'website_url',
        'linkedin_url',
        'github_url',
        'twitter_url',
        'portfolio_url',
        'about_me',
        'profile_photo',
        'cv_file',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class)->orderBy('start_date', 'desc');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class)->orderBy('start_date', 'desc');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class)->orderBy('sort_order', 'asc');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class)->orderBy('sort_order', 'asc');
    }

    // Accessors
    public function getCurrentExperience()
    {
        return $this->experiences()->where('is_current', true)->first();
    }

    public function getSkillsByCategory($category)
    {
        return $this->skills()->where('category', $category)->get();
    }

    public function getYearsOfExperience()
    {
        $experiences = $this->experiences;
        if ($experiences->isEmpty()) {
            return 0;
        }

        $startDate = $experiences->min('start_date');
        return now()->diffInYears($startDate);
    }

    public function getTotalProjects()
    {
        return $this->projects()->count();
    }

    public function getSkillCategories()
    {
        return $this->skills()->distinct()->pluck('category')->toArray();
    }
}
