<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingSessionResource\Pages;
use App\Filament\Resources\TrainingSessionResource\RelationManagers;
use App\Models\TrainingSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrainingSessionResource extends Resource
{
    protected static ?string $model = TrainingSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 1;



     public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255),
                    ]),

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
                    ]),

                Forms\Components\TextInput::make('rank')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->required(),

                Forms\Components\TextInput::make('duration')
                    ->label('Duration (seconds)')
                    ->numeric()
                    ->minValue(0)
                    ->required(),

                Forms\Components\TextInput::make('accuracy')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->required(),

                Forms\Components\TextInput::make('words_trained')
                    ->label('Words Trained')
                    ->numeric()
                    ->minValue(0)
                    ->required(),

                Forms\Components\DateTimePicker::make('started_at')
                    ->required(),

                Forms\Components\DateTimePicker::make('ended_at')
                    ->required(),
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

                Tables\Columns\TextColumn::make('book.title')
                    ->label('Book')
                    ->sortable()
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('rank')
                    ->sortable()
                    ->color(fn ($record) => match (true) {
                        $record->rank >= 80 => 'success',
                        $record->rank >= 60 => 'warning',
                        default => 'danger',
                    }),

                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => gmdate('H:i:s', $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('accuracy')
                    ->suffix('%')
                    ->sortable()
                    ->color(fn ($record) => match (true) {
                        $record->accuracy >= 90 => 'success',
                        $record->accuracy >= 70 => 'warning',
                        default => 'danger',
                    }),

                Tables\Columns\TextColumn::make('words_trained')
                    ->label('Words')
                    ->sortable()
                    ->numeric(),

                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('ended_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('book')
                    ->relationship('book', 'title')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('high_accuracy')
                    ->label('High Accuracy (≥90%)')
                    ->query(fn (Builder $query) => $query->where('accuracy', '>=', 90)),

                Tables\Filters\Filter::make('recent_sessions')
                    ->label('Last 7 days')
                    ->query(fn (Builder $query) => $query->where('created_at', '>=', now()->subDays(7))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingSessions::route('/'),
            'create' => Pages\CreateTrainingSession::route('/create'),
            'edit' => Pages\EditTrainingSession::route('/{record}/edit'),
        ];
    }
}
