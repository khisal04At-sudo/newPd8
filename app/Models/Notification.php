<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'data',
        'is_read',
        'read_at',
        'notifiable_type',
        'notifiable_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // ============ Relationships ============

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    // ============ Scopes ============

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // ============ Helper Methods ============

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Get type icon
     */
    public function getTypeIconAttribute()
    {
        $icons = [
            'application_status' => 'fa-file-signature',
            'opportunity' => 'fa-briefcase',
            'achievement' => 'fa-medal',
            'system' => 'fa-bell',
            'message' => 'fa-envelope',
        ];

        return 'fas ' . ($icons[$this->type] ?? 'fa-bell');
    }

    /**
     * Get type color
     */
    public function getTypeColorAttribute()
    {
        $colors = [
            'application_status' => 'indigo',
            'opportunity' => 'emerald',
            'achievement' => 'amber',
            'system' => 'slate',
            'message' => 'purple',
        ];

        return $colors[$this->type] ?? 'slate';
    }
}
