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
            'application_status' => 'file-text',
            'opportunity' => 'briefcase',
            'achievement' => 'award',
            'system' => 'bell',
            'message' => 'mail',
        ];

        return $icons[$this->type] ?? 'bell';
    }

    /**
     * Get type color
     */
    public function getTypeColorAttribute()
    {
        $colors = [
            'application_status' => 'blue',
            'opportunity' => 'green',
            'achievement' => 'yellow',
            'system' => 'gray',
            'message' => 'purple',
        ];

        return $colors[$this->type] ?? 'gray';
    }
}
