<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'city_id',
        'gender',
        'birth_date',
        'user_type',
        'otp_code',
        'otp_expires_at',
        'last_otp_sent_at',
        'is_verified',
        'is_active',
        'bio',
        'status',
        'volunteer_hours',
        'points',
        'admin_rating',
        'region',
        'phone',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'otp_expires_at' => 'datetime',
        'last_otp_sent_at' => 'datetime',
        'birth_date' => 'date',
        'volunteer_hours' => 'decimal:2',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    // ============ Relationships ============

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function organization()
    {
        return $this->hasOne(Organization::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function interests()
    {
        return $this->hasMany(UserInterest::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    // ============ Helper Methods ============

    /**
     * Check if OTP is valid and not expired
     */
    public function isOtpValid($otp)
    {
        return $this->otp_code === $otp 
            && $this->otp_expires_at 
            && Carbon::now()->isBefore($this->otp_expires_at);
    }

    /**
     * Check if user can resend OTP (1 minute cooldown)
     */
    public function canResendOtp()
    {
        if (!$this->last_otp_sent_at) {
            return true;
        }

        // Use a signed diff: positive means last_otp_sent_at is in the past
        $secondsPassed = Carbon::parse($this->last_otp_sent_at)->diffInSeconds(Carbon::now(), false);

        return $secondsPassed >= 60;
    }

    /**
     * Add points to user
     */
    public function addPoints($points)
    {
        $this->increment('points', $points);
    }

    /**
     * Get user's avatar file
     */
    public function getAvatar()
    {
        return $this->files()->where('file_category', 'avatar')->latest()->first();
    }

    /**
     * Get user's CV file
     */
    public function getCV()
    {
        return $this->files()->where('file_category', 'cv')->latest()->first();
    }

    /**
     * Get avatar URL or default
     */
    public function getAvatarUrlAttribute()
    {
        $avatar = $this->getAvatar();
        return $avatar ? asset($avatar->file_url) : asset('images/default-avatar.png');
    }

    /**
     * Calculate age from birth_date
     */
    public function getAgeAttribute()
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->age : null;
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    // ============ Admin Management Methods ============

    /**
     * Check if user is banned
     */
    public function isBanned()
    {
        return $this->status === 2;
    }

    /**
     * Ban user
     */
    public function ban()
    {
        $this->update(['status' => 2, 'is_active' => false]);
    }

    /**
     * Unban user
     */
    public function unban()
    {
        $this->update(['status' => 1, 'is_active' => true]);
    }

    /**
     * Scope for banned users
     */
    public function scopeBanned($query)
    {
        return $query->where('status', 2);
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1)->where('is_active', true);
    }
}
