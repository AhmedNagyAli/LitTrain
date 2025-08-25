<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name','avatar','bio','about','born_at','died_at','age'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
