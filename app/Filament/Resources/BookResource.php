<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Book Information')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, $set, $get, $operation) {
                            if ($operation === 'create' || ($operation === 'edit' && empty($get('slug')))) {
                                $set('slug', Str::slug($state));
                            }
                        }),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->hint('Auto-generated from title, but you can customize it')
                        ->helperText('URL-friendly version of the title'),
                    Forms\Components\TextInput::make('meta_title')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('description')
                        ->rows(3),
                    Forms\Components\Select::make('author_id')
                        ->relationship('author', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('publisher_id')
                        ->relationship('publisher', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('language_id')
                        ->relationship('language', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('pages_number')
                        ->numeric()
                        ->minValue(1),
                ])->columns(2),

            Forms\Components\Section::make('Media Files')
                ->schema([
                    Forms\Components\FileUpload::make('cover')
                        ->image()
                        ->directory('books/covers')
                        ->visibility('public'),
                    Forms\Components\FileUpload::make('file')
                        ->directory('books/files')
                        ->visibility('public')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(102400) // 100MB in KB
                        ->helperText('Maximum file size: 100MB. Only PDF files are accepted.')
                        ->downloadable()
                        ->previewable(false),
                    Forms\Components\Select::make('file_type')
                        ->options([
                            'pdf' => 'PDF',
                            'epub' => 'EPUB',
                            'mobi' => 'MOBI',
                        ]),
                ])->columns(3),

            Forms\Components\Section::make('Status')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            Book::STATUS_PENDING => 'Pending',
                            Book::STATUS_ACCEPTED => 'Accepted',
                            Book::STATUS_REJECTED => 'Rejected',
                            Book::STATUS_ARCHIVED => 'Archived',
                        ])
                        ->required()
                        ->default(Book::STATUS_PENDING),
                ]),
        ]);
}


     public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover')
                    ->label('Cover')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-book-cover.png')),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('author.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('publisher.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('language.name')
                    ->label('Language')
                    ->sortable(),

                Tables\Columns\TextColumn::make('categories.category')
                    ->label('Categories')
                    ->badge()
                    ->color('primary')
                    ->limitList(3)
                    ->expandableLimitedList(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'warning',
                        '1' => 'success',
                        '2' => 'danger',
                        '3' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => [
                        Book::STATUS_PENDING => 'Pending',
                        Book::STATUS_ACCEPTED => 'Accepted',
                        Book::STATUS_REJECTED => 'Rejected',
                        Book::STATUS_ARCHIVED => 'Archived',
                    ][$state] ?? 'Unknown'),

                Tables\Columns\TextColumn::make('download_count')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('read_count')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        Book::STATUS_PENDING => 'Pending',
                        Book::STATUS_ACCEPTED => 'Accepted',
                        Book::STATUS_REJECTED => 'Rejected',
                        Book::STATUS_ARCHIVED => 'Archived',
                    ])
                    ->label('Status'),

                Tables\Filters\SelectFilter::make('language_id')
                    ->relationship('language', 'name')
                    ->label('Language'),

                Tables\Filters\SelectFilter::make('author_id')
                    ->relationship('author', 'name')
                    ->label('Author'),

                Tables\Filters\SelectFilter::make('categories')
                    ->relationship('categories', 'category')
                    ->multiple()
                    ->preload()
                    ->label('Categories'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('updateCategories')
                        ->label('Update Categories')
                        ->form([
                            Forms\Components\Select::make('categories')
                                ->relationship('categories', 'category')
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->options(Category::all()->pluck('category', 'id'))
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('category')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('slug')
                                        ->maxLength(255),
                                    Forms\Components\Textarea::make('description'),
                                ])
                                ->createOptionUsing(function (array $data) {
                                    $category = Category::create($data);
                                    return $category->id;
                                }),
                        ])
                        ->action(function (Book $record, array $data): void {
                            $record->categories()->sync($data['categories']);
                        }),

                    Tables\Actions\Action::make('updateStatus')
                        ->label('Update Status')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options([
                                    Book::STATUS_PENDING => 'Pending',
                                    Book::STATUS_ACCEPTED => 'Accepted',
                                    Book::STATUS_REJECTED => 'Rejected',
                                    Book::STATUS_ARCHIVED => 'Archived',
                                ])
                                ->required(),
                        ])
                        ->action(function (Book $record, array $data): void {
                            $record->update(['status' => $data['status']]);
                        }),

                    Tables\Actions\Action::make('updateLanguage')
                        ->label('Update Language')
                        ->form([
                            Forms\Components\Select::make('language_id')
                                ->relationship('language', 'language')
                                ->required()
                                ->searchable()
                                ->preload(),
                        ])
                        ->action(function (Book $record, array $data): void {
                            $record->update(['language_id' => $data['language_id']]);
                        }),

                    Tables\Actions\DeleteAction::make(),
                ])->icon('heroicon-m-ellipsis-vertical')
                 ->label('Actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('updateStatusBulk')
                        ->label('Update Status')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options([
                                    Book::STATUS_PENDING => 'Pending',
                                    Book::STATUS_ACCEPTED => 'Accepted',
                                    Book::STATUS_REJECTED => 'Rejected',
                                    Book::STATUS_ARCHIVED => 'Archived',
                                ])
                                ->required(),
                        ])
                        ->action(function ($records, array $data): void {
                            foreach ($records as $record) {
                                $record->update(['status' => $data['status']]);
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // You can add relation managers here later if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['author', 'publisher', 'language']);
    }
}
