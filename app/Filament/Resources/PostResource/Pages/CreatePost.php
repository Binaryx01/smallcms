<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;



    // This is a function to  redirect page after post creation..
    protected function getRedirectUrl(): string
    {
        // Redirects to create page after submission
    
        return static::getResource()::getUrl('index'); 

    }
}
