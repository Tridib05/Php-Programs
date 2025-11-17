<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'cv_profile_id',
        'skill_name',
        'category',
        'proficiency',
        'description',
        'sort_order',
        'icon_class',
        'endorsements',
    ];

    protected $casts = [
        'proficiency' => 'integer',
        'endorsements' => 'array',
    ];

    public function cvProfile(): BelongsTo
    {
        return $this->belongsTo(CVProfile::class);
    }

    public function getProficiencyLevelAttribute()
    {
        if ($this->proficiency >= 90) {
            return 'Expert';
        } elseif ($this->proficiency >= 70) {
            return 'Advanced';
        } elseif ($this->proficiency >= 50) {
            return 'Intermediate';
        } else {
            return 'Beginner';
        }
    }

    public function addEndorsement($person)
    {
        $endorsements = $this->endorsements ?? [];
        if (!in_array($person, $endorsements)) {
            $endorsements[] = $person;
            $this->update(['endorsements' => $endorsements]);
        }
    }

    public function getEndorsementCountAttribute()
    {
        return count($this->endorsements ?? []);
    }
}
