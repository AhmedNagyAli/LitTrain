<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublishingRequestResource\Pages;
use App\Filament\Resources\PublishingRequestResource\RelationManagers;
use App\Models\PublishingRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PublishingRequestResource extends Resource
{
    protected static ?string $model = PublishingRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPublishingRequests::route('/'),
            'create' => Pages\CreatePublishingRequest::route('/create'),
            'edit' => Pages\EditPublishingRequest::route('/{record}/edit'),
        ];
    }
}
