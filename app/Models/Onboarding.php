<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Onboarding extends Model
{
    protected $guarded = [];

    protected $casts = [
        "is_active" => "boolean",
    ];

    public function salons()
    {
        return $this->belongsToMany(Salon::class, 'onboarding_salon');
    }

}
