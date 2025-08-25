<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title','meta_title','description','author_id','publisher_id','cover','file','file_type',
        'download_count','read_count','language_id','pages_number','slug','status'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_categories');
    }

    public function chapters()
    {
        return $this->hasMany(BookChapter::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_saved_books');
    }

    public function ratings()
    {
        return $this->hasMany(BookRating::class);
    }
}
