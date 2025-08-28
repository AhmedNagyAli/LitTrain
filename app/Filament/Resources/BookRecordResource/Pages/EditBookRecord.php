<?php

namespace App\Filament\Resources\BookRecordResource\Pages;

use App\Filament\Resources\BookRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookRecord extends EditRecord
{
    protected static string $resource = BookRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
