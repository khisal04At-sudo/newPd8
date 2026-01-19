<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'opportunity_id',
        'resum_file_id',
        'decision_by',
        'status',
        'cover_letter',
        'applied_at',
        'decision_at',
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
}
