<?php

namespace App\Filament\Resources\RisetResource\Pages;

use App\Filament\Resources\RisetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiset extends EditRecord
{
    protected static string $resource = RisetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string 
    { 
        return $this->getResource()::getUrl('index'); 
    } 
}