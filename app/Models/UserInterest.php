<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInterest extends Model
{
    protected $fillable = ['user_id', 'category'];

    public static $categories = [
        'بيئة'             => ['icon' => 'assets/images/save-plant_17935632.png', 'color' => '#16a34a'],
        'تكنولوجيا'        => ['icon' => 'assets/images/science_2029153.png',    'color' => '#2563eb'],
        'تعليم'            => ['icon' => 'assets/images/writing_9498167.png',      'color' => '#7c3aed'],
        'صحة'              => ['icon' => 'assets/images/healthy_16471520.png',      'color' => '#dc2626'],
        'مساعدة إنسانية'   => ['icon' => 'assets/images/customer-retention_17919670.png',  'color' => '#ea580c'],
        'ريادة أعمال'      => ['icon' => 'assets/images/rethink_2637436.png',         'color' => '#0891b2'],
        'رياضة'            => ['icon' => 'assets/images/physical_5388915.png',         'color' => '#65a30d'],
        'فنون'             => ['icon' => 'assets/images/drawing_14393774.png',        'color' => '#c026d3'],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
