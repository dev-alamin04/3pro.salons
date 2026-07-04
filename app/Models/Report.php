<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'repoted_by');
    }
    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id');
    }
}
