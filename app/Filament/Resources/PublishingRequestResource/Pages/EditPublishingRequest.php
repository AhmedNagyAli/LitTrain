<?php

namespace App\Filament\Resources\PublishingRequestResource\Pages;

use App\Filament\Resources\PublishingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPublishingRequest extends EditRecord
{
    protected static string $resource = PublishingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
