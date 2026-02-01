<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Opportunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'objectives',
        'tasks',
        'training_outcomes',
        'type',
        'category',
        'subcategory',
        'activity_type',
        'execution_method',
        'start_date',
        'end_date',
        'application_deadline',
        'address',
        'seats',
        'age_requirement',
        'skills_requirement',
        'education_level',
        'previous_experience',
        'additional_requirements',
        'status',
        'city_id',
        'total_hours',
        'daily_hours',
        'requires_certification',
        'provides_certificate',
        'requires_cover_letter',
        'is_practical',
        'has_stipend',
        'attendance_required',
        'pre_test_required',
        'contact_name',
        'contact_info',
        'admin_notes',
        'volunteer_type',
        'training_field',
        'training_duration',
        'is_certified',
        'is_paid',
        'cancellation_reason',
        'cancelled_at',
    ];

    const STATUS_REVIEW = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_REJECTED = 3;
    const STATUS_NEEDS_CHANGES = 2;
    const STATUS_UNDER_IMPLEMENTATION = 4;
    const STATUS_COMPLETED = 5;
    const STATUS_CLOSED = 9;
    const STATUS_CANCELLED = 8;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'application_deadline' => 'date',
        'is_practical' => 'boolean',
        'has_stipend' => 'boolean',
        'attendance_required' => 'boolean',
        'pre_test_required' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function certificateFile()
    {
        return $this->hasOne(File::class)->where('file_category', 'certificate');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
