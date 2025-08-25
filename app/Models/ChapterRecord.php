<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChapterRecord extends Model
{
    protected $fillable = ['chapter_id','user_id','record_file','language_id'];

    public function chapter()
    {
        return $this->belongsTo(BookChapter::class);
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
