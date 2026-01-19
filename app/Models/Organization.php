<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_id',
        'name',
        'description',
        'address',
        'phone',
        'logo_url',
        'organization_type',
        'sector',
        'registration_number',
        'established_at',
        'max_volunteers',
        'services',
        'rating',
        'verified',
        'verified_at',
        'social_links',
        'status',
        'rejection_reason',
        'requested_documents',
        'auto_publish_opportunities',
    ];

    protected $casts = [
        'services' => 'json',
        'social_links' => 'json',
        'verified' => 'boolean',
        'auto_publish_opportunities' => 'boolean',
        'verified_at' => 'datetime',
        'established_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class);
    }

    public function verificationDocuments()
    {
        return $this->hasMany(File::class, 'organization_id')->where('file_category', 'verification_document');
    }
}
