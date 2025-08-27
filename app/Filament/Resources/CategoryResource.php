<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Category Information')
                    ->schema([
                        Forms\Components\TextInput::make('category')
                            ->label('Category Name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set) {
                                if (empty($state)) {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('The URL-friendly version of the category name. Auto-generated from the category name.'),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Books in this Category')
                    ->schema([
                        Forms\Components\Placeholder::make('books_count')
                            ->label('Total Books')
                            ->content(function ($record) {
                                return $record ? $record->books()->count() : '0';
                            }),
                    ])
                    ->hidden(fn ($operation) => $operation === 'create'),
            ]);
    }

     public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category')
                    ->label('Category Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('books_count')
                    ->label('Books')
                    ->counts('books')
                    ->numeric()
                    ->sortable()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_books')
                    ->label('Has Books')
                    ->query(fn (Builder $query) => $query->has('books')),

                Tables\Filters\Filter::make('no_books')
                    ->label('No Books')
                    ->query(fn (Builder $query) => $query->doesntHave('books')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('viewBooks')
                        ->label('View Books')
                        ->icon('heroicon-o-book-open')
                        ->url(fn (Category $record) => BookResource::getUrl('index', [
                            'tableFilters' => [
                                'categories' => [
                                    'values' => [$record->id]
                                ]
                            ]
                        ])),

                    Tables\Actions\DeleteAction::make(),
                ])->icon('heroicon-m-ellipsis-vertical')
                 ->label('Actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('updateSlugs')
                        ->label('Update Slugs')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'slug' => Str::slug($record->category)
                                ]);
                            }
                        })
                        ->icon('heroicon-o-arrow-path')
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('category')
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
