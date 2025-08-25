<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['category','slug'];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_categories');
    }
}
