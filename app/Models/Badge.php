<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $guarded = [];

    protected $casts = [
        "is_visible" => "boolean",
        "created_at"  => "datetime"
    ];

    protected $hidden = [
        'updated_at',
        "user_id",
        "assigned_by",
        "piller_id",
        "salon_id",
        "is_visible",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assinedBy()
    {
        return $this->belongsTo(User::class, "assigned_by");
    }

    public function pillar()
    {
        return $this->belongsTo(UserPiller::class, "piller_id");
    }
    public function salon()
    {
        return $this->belongsTo(Salon::class, "salon_id");
    }
}
