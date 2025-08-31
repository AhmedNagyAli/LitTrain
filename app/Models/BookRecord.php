<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookRecord extends Model
{
    protected $fillable = [
        'book_id',
        'user_id',
        'record_file',
        'duration',
        'language_id',
        'status',
        'rejected_reason', // ✅ new field
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
