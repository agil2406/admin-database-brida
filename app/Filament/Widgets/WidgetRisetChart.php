<?php

namespace App\Filament\Widgets;

use App\Models\Riset;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class WidgetRisetChart extends ChartWidget
{
    protected static ?string $heading = 'Riset Line Diagram';

    protected static string $color = 'warning';

    protected static bool $isLazy = true;

    protected function getData(): array
    {

        $activeFilter = $this->filter;

        $data = Trend::model(Riset::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Riset / Kajian',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(function (TrendValue $value) {
                $date = Carbon::createFromFormat('Y-m', $value->date);
                $formattedDate = $date->format('M');

                return $formattedDate;
            }),
        ];
    }





    protected function getType(): string
    {
        return 'line';
    }


    public function getDescription(): ?string
    {
        return 'Jumlah Riset / Kajian setiap bulan pada tahun ' . now()->year;
    }
}
