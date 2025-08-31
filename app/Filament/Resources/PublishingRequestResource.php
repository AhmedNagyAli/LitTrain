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

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->directory('publishing-requests')
                    ->maxSize(2048)
                    ->columnSpanFull()
                    ->downloadable()
                    ->openable(),

                Forms\Components\Toggle::make('is_accepted')
                    ->label('Is Accepted')
                    ->required()
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_accepted')
                    ->label('Accepted')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('accepted')
                    ->label('Accepted Requests')
                    ->query(fn (Builder $query) => $query->where('is_accepted', true)),

                Tables\Filters\Filter::make('pending')
                    ->label('Pending Requests')
                    ->query(fn (Builder $query) => $query->where('is_accepted', false)),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                // Custom action for accepting/rejecting requests
                Tables\Actions\Action::make('toggleAcceptance')
                    ->label(fn (PublishingRequest $record) => $record->is_accepted ? 'Reject' : 'Accept')
                    ->icon(fn (PublishingRequest $record) => $record->is_accepted ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (PublishingRequest $record) => $record->is_accepted ? 'danger' : 'success')
                    ->action(function (PublishingRequest $record) {
                        $record->update(['is_accepted' => !$record->is_accepted]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading(fn (PublishingRequest $record) => $record->is_accepted ? 'Reject Request' : 'Accept Request')
                    ->modalDescription(fn (PublishingRequest $record) => $record->is_accepted
                        ? 'Are you sure you want to reject this publishing request?'
                        : 'Are you sure you want to accept this publishing request?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // Bulk accept action
                    Tables\Actions\BulkAction::make('accept')
                        ->label('Accept Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['is_accepted' => true]);
                        })
                        ->requiresConfirmation(),

                    // Bulk reject action
                    Tables\Actions\BulkAction::make('reject')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each->update(['is_accepted' => false]);
                        })
                        ->requiresConfirmation(),
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
