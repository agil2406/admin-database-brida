<?php

namespace App\Filament\Widgets;

use App\Models\Eduwisata;
use App\Models\Inovasi;
use App\Models\Riset;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $inovasiCount = Inovasi::count();
        $risetCount = Riset::count();
        $eduwisataCount = Eduwisata::count();

        return [
            Stat::make('Jumlah Inovasi', $inovasiCount)
                ->description('Total semua data inovasi')
                ->color('gray'),
            Stat::make('Jumlah Riset', $risetCount)
                ->description('Total semua data riset')
                ->color('gray'),
            Stat::make('Jumlah Eduwisata', $eduwisataCount)
                ->description('Total semua pengunjung eduwisata')
                ->color('gray'),
        ];
    }
}
