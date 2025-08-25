<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWritingRank extends Model
{
    protected $fillable = ['user_id','rank'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
