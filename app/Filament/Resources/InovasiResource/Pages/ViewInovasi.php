<?php

namespace App\Filament\Resources\InovasiResource\Pages;

use App\Filament\Resources\InovasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInovasi extends ViewRecord
{
    protected static string $resource = InovasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
