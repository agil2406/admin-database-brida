<?php

namespace App\Filament\Resources\InovasiResource\Widgets;

use App\Models\Inovasi;
use App\Models\Riset;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsInovasi extends BaseWidget
{
    protected function getStats(): array
    {
        $inovasiCount = Inovasi::count();
        $risetCount = Riset::count();
        $totalData = $inovasiCount + $risetCount;

        return [
            Stat::make('Jumlah Inovasi', $inovasiCount)
                ->description('Total semua inovasi')
                ->color('primary'),
            Stat::make('Jumlah Riset', $risetCount)
                ->description('Total semua riset')
                ->color('success'),
            Stat::make('Total Data', $totalData)
                ->description('Inovasi + Riset')
                ->color('warning'),
        ];
    }
}
