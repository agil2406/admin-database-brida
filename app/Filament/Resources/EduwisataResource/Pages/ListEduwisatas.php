<?php

namespace App\Filament\Resources\EduwisataResource\Pages;

use App\Filament\Resources\EduwisataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEduwisatas extends ListRecords
{
    protected static string $resource = EduwisataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
