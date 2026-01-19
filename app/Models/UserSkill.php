<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_name',
        'proficiency_level',
        'years_of_experience',
    ];

    // ============ Relationships ============

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ============ Helper Methods ============

    /**
     * Get proficiency label in Arabic
     */
    public function getProficiencyLabelAttribute()
    {
        $labels = [
            'beginner' => 'مبتدئ',
            'intermediate' => 'متوسط',
            'advanced' => 'متقدم',
            'expert' => 'خبير',
        ];

        return $labels[$this->proficiency_level] ?? $this->proficiency_level;
    }

    /**
     * Get proficiency color for badges
     */
    public function getProficiencyColorAttribute()
    {
        $colors = [
            'beginner' => 'gray',
            'intermediate' => 'blue',
            'advanced' => 'green',
            'expert' => 'purple',
        ];

        return $colors[$this->proficiency_level] ?? 'gray';
    }
}
