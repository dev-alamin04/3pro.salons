<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable, HasProfilePhoto;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'provider_id',
        'provider',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_used_key',
        'metadata',
        'term_accept',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'is_active'         => 'boolean',
        'password'          => 'hashed',
        'id'                => 'integer',
        'is_premium'        => 'boolean',
        'term_accept'       => 'boolean',
        'deleted_at'        => 'datetime',
        'metadata'          => 'array',
        'joined_at'         => 'datetime',

        'is_used_key'       => 'boolean',
    ];

    // Scopes
    public function scopeVerified($q)
    {
        return $q->whereNotNull('email_verified_at');
    }
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class, 'user_id');
    }

    public function mysalon()
    {
        return $this->hasMany(UserSalon::class, 'user_id');
    }

    public function currentSalon()
    {
        return $this->hasOne(UserSalon::class, 'user_id')->where('is_current', true);
    }

    public function salon_assigned_by()
    {
        return $this->hasMany(UserSalon::class, 'assined_by');
    }

    public function mygoal()
    {
        return $this->hasMany(Goal::class, 'user_id');
    }
    public function goal_assigned_by()
    {
        return $this->hasMany(Goal::class, 'assigned_by');
    }

    public function myPiller()
    {
        return $this->hasMany(UserPiller::class, 'user_id');
    }

    public function badge_assigned_by()
    {
        return $this->hasMany(Badge::class, 'assigned_by');
    }

    public function myBadges()
    {
        return $this->hasMany(Badge::class, 'user_id');
    }

    public function task_assinged_by()
    {
        return $this->hasMany(DailyTask::class, 'assigned_by');
    }

    public function myTask()
    {
        return $this->hasMany(DailyTask::class, 'user_id');
    }

    public function userSkill()
    {
        return $this->hasMany(UserSkill::class, 'user_id');
    }

    public function report_assigned_by()
    {
        return $this->hasMany(Report::class, 'repoted_by');
    }

    public function myReport()
    {
        return $this->hasMany(Report::class, 'user_id');
    }
}
