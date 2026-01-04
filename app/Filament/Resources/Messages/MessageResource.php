<?php

namespace App\Filament\Resources\Messages; // Check if your folder is 'Messages' or just 'Resources'

use App\Filament\Resources\Messages\Pages; // Ensure this matches your folder structure
use App\Models\Message;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->required(),
            Textarea::make('content')->required(),
            Select::make('user_id')
                ->relationship('user', 'name', fn (Builder $query) => $query->whereHas('roles', fn($q) => $q->where('name', 'student')))
                ->label('Recipient Student')
                ->required()
                ->searchable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('user.name')->label('Recipient')->sortable(),
                TextColumn::make('created_at')->dateTime()->label('Sent At'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        /** @var User $user */
        $user = Auth::user();

        if ($user && method_exists($user, 'hasRole') && $user->hasRole('student')) {
            return $query->where('user_id', $user->id);
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessages::route('/'),
            'create' => Pages\CreateMessage::route('/create'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }
}