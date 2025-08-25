<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $fillable = [
        'name','avatar','bio','about','born_at','age','type','is_verified'
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
