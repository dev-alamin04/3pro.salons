<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSalon extends Model
{
    protected $guarded = [];
    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
