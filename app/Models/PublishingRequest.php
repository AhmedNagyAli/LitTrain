<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublishingRequest extends Model
{
    protected $fillable = ['user_id','description','image','is_accepted'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
