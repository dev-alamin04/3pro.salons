<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserSalon extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'is_current',
        'assined_by',
    ];
    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeTeam(Builder $query, $salon_id, ?string $role = null)
    {
        return $query->where('salon_id', $salon_id)
            ->whereHas('user', function ($q) use ($role) {
                $q->where('role', '!=', $role ?? 'admin');
            });
    }

    public function scopeCurrent(Builder $query)
    {
        return $query->where('is_current', true);
    }
}
