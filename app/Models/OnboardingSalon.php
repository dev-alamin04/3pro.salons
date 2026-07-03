<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingSalon extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'salon_id',
    ];
    protected $table   = 'onboarding_salon';
}
