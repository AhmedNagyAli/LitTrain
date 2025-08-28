<?php

namespace App\Filament\Resources\BookRecordResource\Pages;

use App\Filament\Resources\BookRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookRecords extends ListRecords
{
    protected static string $resource = BookRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
