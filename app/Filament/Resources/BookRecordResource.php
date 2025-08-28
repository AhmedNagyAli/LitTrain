<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookRecordResource\Pages;
use App\Filament\Resources\BookRecordResource\RelationManagers;
use App\Models\BookRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                    ->relationship('book', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('author')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('language_id')
                    ->label('Language')
                    ->relationship('language', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->maxLength(10),
                    ]),

                Forms\Components\FileUpload::make('record_file')
                    ->label('Audio File')
                    //->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/mp3'])
                     //->maxSize(102400) // 100MB (value is in KB)
                    ->directory('book/records')
                    ->required()
                    ->downloadable(),

                Forms\Components\TextInput::make('duration')
                    ->label('Duration (seconds)')
                    ->numeric()
                    ->minValue(1)
                    ->required(),
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

                Tables\Columns\TextColumn::make('language.name')
                    ->label('Language')
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => gmdate('H:i:s', $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('record_file')
                    ->label('File')
                    ->searchable(),

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
                Tables\Filters\SelectFilter::make('book')
                    ->relationship('book', 'title')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('language')
                    ->relationship('language', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('duration')
                    ->form([
                        Forms\Components\TextInput::make('min_duration')
                            ->label('Min Duration (seconds)')
                            ->numeric(),
                        Forms\Components\TextInput::make('max_duration')
                            ->label('Max Duration (seconds)')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_duration'],
                                fn (Builder $query, $minDuration): Builder => $query->where('duration', '>=', $minDuration)
                            )
                            ->when(
                                $data['max_duration'],
                                fn (Builder $query, $maxDuration): Builder => $query->where('duration', '<=', $maxDuration)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListBookRecords::route('/'),
            'create' => Pages\CreateBookRecord::route('/create'),
            'edit' => Pages\EditBookRecord::route('/{record}/edit'),
        ];
    }
}
