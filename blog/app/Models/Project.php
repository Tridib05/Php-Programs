<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'cv_profile_id',
        'project_name',
        'description',
        'detailed_description',
        'project_url',
        'github_url',
        'start_date',
        'end_date',
        'is_current',
        'technologies',
        'images',
        'featured_image',
        'impact',
        'sort_order',
        'submitted_by',
        'is_approved',
        'submission_type',
        'submission_email',
        'submission_website',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'technologies' => 'array',
        'images' => 'array',
    ];

    public function cvProfile(): BelongsTo
    {
        return $this->belongsTo(CVProfile::class);
    }

    public function getDisplayDateAttribute()
    {
        $start = $this->start_date->format('M Y');
        $end = $this->is_current ? 'Ongoing' : $this->end_date->format('M Y');
        
        return "{$start} - {$end}";
    }

    public function getTechStackAttribute()
    {
        return implode(', ', $this->technologies ?? []);
    }

    public function getImageCountAttribute()
    {
        return count($this->images ?? []);
    }
}
