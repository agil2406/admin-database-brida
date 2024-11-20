<?php

namespace App\Filament\Resources\EduwisataResource\Pages;

use App\Filament\Resources\EduwisataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEduwisata extends EditRecord
{
    protected static string $resource = EduwisataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string 
    { 
        return $this->getResource()::getUrl('index'); 
    } 
}
