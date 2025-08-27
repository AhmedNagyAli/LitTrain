<?php

namespace App\Filament\Resources\PublishingRequestResource\Pages;

use App\Filament\Resources\PublishingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPublishingRequests extends ListRecords
{
    protected static string $resource = PublishingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
