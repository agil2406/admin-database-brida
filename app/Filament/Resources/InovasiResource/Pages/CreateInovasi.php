<?php

namespace App\Filament\Resources\InovasiResource\Pages;

use App\Filament\Resources\InovasiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInovasi extends CreateRecord
{
    protected static string $resource = InovasiResource::class;

    protected static bool $canCreateAnother = false;
    
    protected function getRedirectUrl(): string 
    { 
        return $this->getResource()::getUrl('index'); 
    } 
}
