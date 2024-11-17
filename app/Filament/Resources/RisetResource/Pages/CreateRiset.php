<?php

namespace App\Filament\Resources\RisetResource\Pages;

use App\Filament\Resources\RisetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRiset extends CreateRecord
{
    protected static string $resource = RisetResource::class;

    protected static bool $canCreateAnother = false;

    
    protected function getRedirectUrl(): string 
    { 
        return $this->getResource()::getUrl('index'); 
    } 
}