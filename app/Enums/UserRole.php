<?php

namespace App\Enums;

enum UserRole: string
{
    case Reader = 'reader';
    case Writer = 'writer';
    case Admin  = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Reader => 'Reader',
            self::Writer => 'Writer',
            self::Admin  => 'Admin',
        };
    }
}
