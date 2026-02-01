<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXECUTING = 'executing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'opportunity_id',
        'resum_file_id',
        'decision_by',
        'status',
        'cover_letter',
        'applied_at',
        'decision_at',
        'attended_hours',
        'commitment_score',
        'evaluation_notes',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'decision_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function resumFile()
    {
        return $this->belongsTo(File::class, 'resum_file_id');
    }

    public function decidedBy()
    {
        return $this->belongsTo(User::class, 'decision_by');
    }
}
