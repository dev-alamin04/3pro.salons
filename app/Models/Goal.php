<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $guarded = [];

    protected $casts = [
        "target_date" => "datetime",
        "is_active"   => "boolean",
        "is_public"   => "boolean",
    ];

    public function assinedBy()
    {
        return $this->belongsTo(User::class, "assigned_by");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
