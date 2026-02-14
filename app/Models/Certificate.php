<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'opportunity_id',
        'application_id',
        'file_id',
        'file_url',
        'title',
        'certificate_number',
        'issue_date',
        'is_downloadable',
        'attendance_percentage',
        'total_hours',
        'attended_hours',
        'organization_name',
        'opportunity_title',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'is_downloadable' => 'boolean',
        'attendance_percentage' => 'decimal:2',
    ];

    // ============ Relationships ============

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    // ============ Helper Methods ============

    /**
     * Get certificate download URL
     */
    public function getDownloadUrlAttribute()
    {
        if (!$this->is_downloadable || !$this->file) {
            return null;
        }

        return route('certificates.download', $this->id);
    }
}
