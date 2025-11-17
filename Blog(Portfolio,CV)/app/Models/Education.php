<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'cv_profile_id',
        'school_name',
        'degree',
        'field_of_study',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'gpa',
        'activities',
        'school_website',
        'sort_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'activities' => 'array',
        'gpa' => 'float',
    ];

    public function cvProfile(): BelongsTo
    {
        return $this->belongsTo(CVProfile::class);
    }

    public function getDisplayDateAttribute()
    {
        $start = $this->start_date->format('M Y');
        $end = $this->is_current ? 'Present' : $this->end_date->format('M Y');
        
        return "{$start} - {$end}";
    }

    public function getFullQualificationAttribute()
    {
        return "{$this->degree} in {$this->field_of_study}";
    }
}
