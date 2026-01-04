<?php

namespace App\Filament\Resources\Marks;

use App\Filament\Resources\Marks\Pages\CreateMark;
use App\Filament\Resources\Marks\Pages\EditMark;
use App\Filament\Resources\Marks\Pages\ListMarks;
use App\Models\Mark;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MarkResource extends Resource
{
    protected static ?string $model = Mark::class;

     protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('student_id')
                ->relationship('student', 'name', fn (Builder $query) => $query->whereHas('roles', fn($q) => $q->where('name', 'student')))
                ->required()
                ->searchable(),
            Select::make('subject_id')
                ->relationship('subject', 'name')
                ->required(),
            TextInput::make('score')
                ->numeric()
                ->required()
                ->minValue(0)
                ->maxValue(100),
            Hidden::make('teacher_id')
                ->default(fn () => Auth::id()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')->label('Student')->sortable(),
                TextColumn::make('subject.name')->label('Subject'),
                TextColumn::make('score')->badge()->color('success'),
                TextColumn::make('teacher.name')->label('Teacher'),
                TextColumn::make('created_at')->dateTime()->label('Date'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // If no user is logged in, return empty
        if (! $user) {
            return $query->whereRaw('1=0');
        }

        // If Student role exists, filter by student_id
        if ((isset($user->role) && $user->role === 'student')
            || (method_exists($user, 'hasRole') && $user->hasRole('student'))
        ) {
            return $query->where('student_id', $user->id);
        }

        // If Teacher role exists, filter by marks they assigned
        if ((isset($user->role) && $user->role === 'teacher')
            || (method_exists($user, 'hasRole') && $user->hasRole('teacher'))
        ) {
            return $query->where('teacher_id', $user->id);
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMarks::route('/'),
            'create' => CreateMark::route('/create'),
            'edit' => EditMark::route('/{record}/edit'),
        ];
    }
}