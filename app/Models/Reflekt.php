<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Reflekt extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(ReflektLike::class);
    }

    public function likedBy()
    {
        return $this->likes()->where('user_id', Auth::user()->id);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
