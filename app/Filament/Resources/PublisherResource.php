<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublisherResource\Pages;
use App\Filament\Resources\PublisherResource\RelationManagers;
use App\Models\Publisher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class PublisherResource extends Resource
{
    protected static ?string $model = Publisher::class;

     protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Publisher Information')
                    ->schema([
                        Forms\Components\FileUpload::make('avatar')
                            ->label('Publisher Logo/Avatar')
                            ->image()
                            ->directory('publishers/avatars')
                            ->visibility('public')
                            ->imagePreviewHeight(150)
                            ->circleCropper()
                            ->panelLayout('integrated')
                            ->maxSize(2048),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('type')
                            ->options([
                                'individual' => 'Individual',
                                'company' => 'Company',
                                'organization' => 'Organization',
                            ])
                            ->required()
                            ->default('company'),

                        Forms\Components\DatePicker::make('born_at')
                            ->label('Founded Date')
                            ->maxDate(now()),

                        Forms\Components\TextInput::make('age')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(150)
                            ->suffix('years'),

                        Forms\Components\Toggle::make('is_verified')
                            ->label('Verified Publisher')
                            ->default(false),
                    ])->columns(2),

                Forms\Components\Section::make('Description')
                    ->schema([
                        Forms\Components\Textarea::make('bio')
                            ->label('Short Bio')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Short description (max 500 characters)'),

                        Forms\Components\RichEditor::make('about')
                            ->label('About Publisher')
                            ->maxLength(5000)
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike',
                                'blockquote', 'bulletList', 'orderedList',
                                'link',
                            ]),
                    ]),

                Forms\Components\Section::make('Statistics')
                    ->schema([
                        Forms\Components\Placeholder::make('books_count')
                            ->label('Total Books')
                            ->content(function ($record) {
                                return $record ? $record->books()->count() : '0';
                            }),

                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created At')
                            ->content(fn ($record) => $record?->created_at?->format('M d, Y H:i')),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Updated At')
                            ->content(fn ($record) => $record?->updated_at?->format('M d, Y H:i')),
                    ])
                    ->columns(3)
                    ->hidden(fn ($operation) => $operation === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-publisher-avatar.png')),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'individual' => 'info',
                        'company' => 'success',
                        'organization' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('books_count')
                    ->label('Books')
                    ->counts('books')
                    ->numeric()
                    ->sortable()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('born_at')
                    ->label('Founded')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('age')
                    ->label('Age')
                    ->numeric()
                    ->sortable()
                    ->suffix(' yrs')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'individual' => 'Individual',
                        'company' => 'Company',
                        'organization' => 'Organization',
                    ])
                    ->label('Type'),

                Tables\Filters\Filter::make('is_verified')
                    ->label('Verified Publishers')
                    ->query(fn (Builder $query) => $query->where('is_verified', true)),

                Tables\Filters\Filter::make('has_books')
                    ->label('Has Books')
                    ->query(fn (Builder $query) => $query->has('books')),

                Tables\Filters\Filter::make('no_books')
                    ->label('No Books')
                    ->query(fn (Builder $query) => $query->doesntHave('books')),

                Tables\Filters\TernaryFilter::make('born_at')
                    ->label('Has Founded Date')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('viewBooks')
                        ->label('View Books')
                        ->icon('heroicon-o-book-open')
                        ->url(fn (Publisher $record) => BookResource::getUrl('index', [
                            'tableFilters' => [
                                'publisher_id' => [
                                    'value' => $record->id
                                ]
                            ]
                        ])),

                    Tables\Actions\Action::make('toggleVerification')
                        ->label(fn (Publisher $record) => $record->is_verified ? 'Unverify' : 'Verify')
                        ->icon(fn (Publisher $record) => $record->is_verified ? 'heroicon-o-x-mark' : 'heroicon-o-check-badge')
                        ->color(fn (Publisher $record) => $record->is_verified ? 'warning' : 'success')
                        ->action(function (Publisher $record) {
                            $record->update(['is_verified' => !$record->is_verified]);
                        })
                        ->requiresConfirmation(),

                    Tables\Actions\DeleteAction::make(),
                ])->icon('heroicon-m-ellipsis-vertical')
                 ->label('Actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('verifyPublishers')
                        ->label('Verify Selected')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_verified' => true]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('unverifyPublishers')
                        ->label('Unverify Selected')
                        ->icon('heroicon-o-x-mark')
                        ->color('warning')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_verified' => false]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('name')
            ->searchable();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('books');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPublishers::route('/'),
            'create' => Pages\CreatePublisher::route('/create'),
            'edit' => Pages\EditPublisher::route('/{record}/edit'),
        ];
    }
}
