<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserPiller extends Model
{
    protected $guarded = [];

    public function scopePillerUpdate(Builder $query, string $name)
    {
        return $query->where('name', $name);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
