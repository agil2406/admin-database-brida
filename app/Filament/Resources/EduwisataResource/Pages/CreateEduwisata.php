<?php

namespace App\Filament\Resources\EduwisataResource\Pages;

use App\Filament\Resources\EduwisataResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEduwisata extends CreateRecord
{
    protected static string $resource = EduwisataResource::class;

    protected static bool $canCreateAnother = false;
    
    protected function getRedirectUrl(): string 
    { 
        return $this->getResource()::getUrl('index'); 
    } 
}
