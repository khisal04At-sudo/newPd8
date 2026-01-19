<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'points_awarded',
        'unlock_criteria',
        'is_active',
    ];

    protected $casts = [
        'unlock_criteria' => 'array',
        'is_active' => 'boolean',
    ];

    // ============ Relationships ============

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    // ============ Scopes ============

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ============ Helper Methods ============

    /**
     * Check if user has earned this achievement
     */
    public function isEarnedBy(User $user)
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Award achievement to user
     */
    public function awardTo(User $user)
    {
        if ($this->isEarnedBy($user)) {
            return false;
        }

        $this->users()->attach($user->id, ['earned_at' => now()]);
        $user->addPoints($this->points_awarded);

        // Create notification
        Notification::create([
            'user_id' => $user->id,
            'title' => 'إنجاز جديد!',
            'message' => "تهانينا! لقد حصلت على إنجاز: {$this->name}",
            'type' => 'achievement',
            'notifiable_type' => Achievement::class,
            'notifiable_id' => $this->id,
        ]);

        return true;
    }
}
