<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookRecordResource\Pages;
use App\Filament\Resources\BookRecordResource\RelationManagers;
use App\Models\Book;
use App\Models\BookRecord;
use App\Models\Language;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookRecordResource extends Resource
{
    protected static ?string $model = BookRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $navigationGroup = 'Library';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('book_id')
                    ->label('Book')
                    ->options(Book::all()->pluck('title', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\FileUpload::make('record_file')
                    ->label('Record File')
                    ->required()
                    ->preserveFilenames()
                    ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/mp3'])
                    ->maxSize(10240), // 10MB max

                Forms\Components\TextInput::make('duration')
                    ->label('Duration (seconds)')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('language_id')
                    ->label('Language')
                    ->options(Language::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),

                Forms\Components\Textarea::make('rejected_reason')
                    ->label('Rejection Reason')
                    ->visible(fn ($get) => $get('status') === 'rejected')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('book.title')
                    ->label('Book')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration (s)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('language.name')
                    ->label('Language')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),

                Tables\Columns\TextColumn::make('rejected_reason')
                    ->label('Rejection Reason')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

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
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

                SelectFilter::make('book_id')
                    ->label('Book')
                    ->options(Book::all()->pluck('title', 'id'))
                    ->searchable(),

                SelectFilter::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable(),

                SelectFilter::make('language_id')
                    ->label('Language')
                    ->options(Language::all()->pluck('name', 'id'))
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBookRecords::route('/'),
            'create' => Pages\CreateBookRecord::route('/create'),
            'edit' => Pages\EditBookRecord::route('/{record}/edit'),
        ];
    }
}
