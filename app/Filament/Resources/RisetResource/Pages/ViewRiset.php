<?php

namespace App\Filament\Resources\RisetResource\Pages;

use App\Filament\Resources\RisetResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRiset extends ViewRecord
{
    protected static string $resource = RisetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
