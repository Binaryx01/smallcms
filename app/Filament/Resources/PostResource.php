<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Doctrine\DBAL\Query\Limit;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Symfony\Contracts\Service\Attribute\Required;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Fill it correctly')
            ->schema([

                //For id
                TextInput::make('id')
                ->label('ID')
                ->disabled()
                ->default(fn ($record) => $record ? $record->id : null),




            // Title Field
            TextInput::make('title')
                ->required()                     // Make the title field required
                ->maxLength(500)        // Set a maximum length of 500 characters
                ->columnSpanFull(),            // Make the field span the full width of the column
    
            // Content Field
            RichEditor::make('content')
                ->required()                          // Make the content field required
                ->dehydrateStateUsing(fn ($state) => strip_tags($state))  // Strip HTML tags
                ->columnSpanFull(),                   // Make the field span the full width of the column
    
            // Summary Field
            TextInput::make('summary')
                ->columnSpanFull(),                   // Make the field span the full width of the column
       
       
                ])
            ]);
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                TextColumn::make('id')
                    ->label('ID') 
                    ->sortable(),   
                
                TextColumn::make('title'),

                TextColumn::make('content')
                ->limit(50),


                TextColumn::make('summary')
                ->limit(50)







            ])
            ->filters([
                //
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
            //
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
