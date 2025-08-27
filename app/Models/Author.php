<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name','avatar','bio','about','born_at','died_at','age'];

    protected $casts = [
        'born_at' => 'date',
        'died_at' => 'date',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
    // Accessor for age if not stored in database
    public function getAgeAttribute()
    {
        if ($this->attributes['age']) {
            return $this->attributes['age'];
        }

        if ($this->born_at) {
            $endDate = $this->died_at ?: now();
            return $this->born_at->diffInYears($endDate);
        }

        return null;
    }
}
