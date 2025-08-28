<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
class User extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $fillable = [
        'name','email','phone','bio','password','avatar','date_of_birth',
        'verification_code','is_email_verified','language_id','role'
    ];
    protected $casts = [
        'role' => UserRole::class,
    ];

    protected $hidden = ['password', 'verification_code'];

    public function publishingRequests()
    {
        return $this->hasMany(PublishingRequest::class);
    }

    public function savedBooks()
    {
        return $this->belongsToMany(Book::class, 'user_saved_books');
    }

    public function writingRank()
    {
        return $this->hasOne(UserWritingRank::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function ratings()
    {
        return $this->hasMany(BookRating::class);
    }

    public function trainingSessions()
    {
        return $this->hasMany(TrainingSession::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }
}
