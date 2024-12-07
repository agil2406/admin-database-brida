<?php

namespace App\Filament\Widgets;

use App\Models\Inovasi;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class WidgetInovasiChart extends ChartWidget
{
    protected static ?string $heading = 'Inovasi Bar Diagram';

    protected static string $color = 'success';

    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $data = Trend::model(Inovasi::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Inovasi',
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
        return 'bar';
    }


    public function getDescription(): ?string
    {
        return 'Jumlah Inovasi setiap bulan pada tahun ' . now()->year;
    }
}
