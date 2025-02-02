<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReflektLike extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reflekt()
    {
        return $this->belongsTo(Reflekt::class);
    }
}
