<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSalon extends Model
{
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}
