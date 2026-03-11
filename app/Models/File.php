<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'opportunity_id',
        'file_name',
        'file_type',
        'file_url',
        'file_size',
        'file_category',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    // ============ Relationships ============

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    // ============ Scopes ============

    public function scopeByCategory($query, $category)
    {
        return $query->where('file_category', $category);
    }

    public function scopeCv($query)
    {
        return $query->where('file_category', 'cv');
    }

    public function scopeAvatar($query)
    {
        return $query->where('file_category', 'avatar');
    }

    public function scopeCertificates($query)
    {
        return $query->where('file_category', 'certificate');
    }

    // ============ Helper Methods ============

    /**
     * Get file download URL
     */
    public function getDownloadUrl()
    {
        return route('files.download', $this->id);
    }

    /**
     * Delete file from storage and database
     */
    public function deleteFile()
    {
        if (Storage::disk('public')->exists($this->file_url)) {
            Storage::disk('public')->delete($this->file_url);
        }
        
        return $this->delete();
    }

    /**
     * Get human readable file size
     */
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
