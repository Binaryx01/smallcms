<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\BelongsToManyCheckboxList;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select; // Correct import for Select
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Fill it correctly')->schema([
                // For id
                TextInput::make('id')
                    ->label('ID')
                    ->disabled()
                    ->default(fn ($record) => $record ? $record->id : null),

                // Title Field
                TextInput::make('title')
                    ->required()
                    ->maxLength(500)
                    ->columnSpanFull(),

                // Content Field
                RichEditor::make('content')
                    ->required()
                    ->dehydrateStateUsing(fn ($state) => strip_tags($state))
                    ->columnSpanFull(),

                // Summary Field
                TextInput::make('summary')
                    ->columnSpanFull(),

                // Categories Field (many-to-many relationship)
                Select::make('categories') // Use the relationship name
                    ->relationship('categories', 'name') // 'name' is the column to display
                    ->multiple() // Allow multiple selections
                    ->preload() // Preload options for better performance
                    ->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Post::query()->latest()) // Orders posts by created_at descending
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('title'),

                TextColumn::make('content')
                    ->limit(50),

                // Display categories as a comma-separated list
                TextColumn::make('categories.name')
                    ->label('Categories')
                    ->formatStateUsing(function ($state, Post $post) {
                        return $post->categories->pluck('name')->implode(', ');
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('summary')
                    ->limit(50),
            ])
            ->filters([
                // Add filters here
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
            // Add relation managers here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}