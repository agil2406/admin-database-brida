<?php

namespace App\Filament\Resources\RisetResource\Pages;

use App\Filament\Resources\RisetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRisets extends ListRecords
{
    protected static string $resource = RisetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
