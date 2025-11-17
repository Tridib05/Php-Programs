<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'cv_profile_id',
        'company_name',
        'job_title',
        'employment_type',
        'description',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'company_website',
        'key_achievements',
        'sort_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'key_achievements' => 'array',
    ];

    public function cvProfile(): BelongsTo
    {
        return $this->belongsTo(CVProfile::class);
    }

    public function getDurationAttribute()
    {
        $start = $this->start_date;
        $end = $this->end_date ?? now();
        
        $years = $start->diffInYears($end);
        $months = $start->copy()->addYears($years)->diffInMonths($end);
        
        if ($years > 0) {
            return "{$years}y {$months}m";
        }
        
        return "{$months}m";
    }

    public function getDisplayDateAttribute()
    {
        $start = $this->start_date->format('M Y');
        $end = $this->is_current ? 'Present' : $this->end_date->format('M Y');
        
        return "{$start} - {$end}";
    }
}
