<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookChapter extends Model
{
    protected $fillable = ['book_id','title','meta_title','description','pages_count','chapter_text'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function records()
    {
        return $this->hasMany(ChapterRecord::class, 'chapter_id');
    }
}
