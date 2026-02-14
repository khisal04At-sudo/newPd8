<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpportunityReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'opportunity_id',
        'application_id',
        'rating',
        'review_comment',
        'would_recommend',
    ];

    protected $casts = [
        'would_recommend' => 'boolean',
    ];

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
}
