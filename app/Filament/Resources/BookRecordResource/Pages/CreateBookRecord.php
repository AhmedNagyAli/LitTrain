<?php

namespace App\Filament\Resources\BookRecordResource\Pages;

use App\Filament\Resources\BookRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookRecord extends CreateRecord
{
    protected static string $resource = BookRecordResource::class;
}
