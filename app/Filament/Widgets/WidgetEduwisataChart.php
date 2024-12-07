<?php

namespace App\Filament\Widgets;

use App\Models\Eduwisata;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class WidgetEduwisataChart extends ChartWidget
{
    protected static ?string $heading = 'Eduwisata Diagram';

    protected static bool $isLazy = true;

    protected function getData(): array
    {

        $activeFilter = $this->filter ?: now()->year;
        // Ambil data yang sudah dikelompokkan berdasarkan tahun dan bulan
        $data = Eduwisata::selectRaw('YEAR(jadwal_kunjungan) as year, MONTH(jadwal_kunjungan) as month, SUM(jumlah_peserta) as total_peserta')
            ->whereYear('jadwal_kunjungan', $activeFilter) // Filter berdasarkan tahun yang dipilih
            ->groupByRaw('YEAR(jadwal_kunjungan), MONTH(jadwal_kunjungan)')
            ->orderByRaw('YEAR(jadwal_kunjungan), MONTH(jadwal_kunjungan)')
            ->get();

        // Inisialisasi label bulan
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // Data awal 0 untuk setiap bulan
        $chartData = array_fill(0, 12, 0);

        // Map data ke dalam array chartData
        foreach ($data as $item) {
            if ($item->month >= 1 && $item->month <= 12) {
                $monthIndex = $item->month - 1; // Konversi bulan ke index (0-11)
                $chartData[$monthIndex] = $item->total_peserta;
            }
        }

        // Return data untuk chart.js
        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Peserta per Bulan',
                    'data' => $chartData,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getFilters(): ?array
    {
        // Ambil tahun-tahun unik dari kolom jadwal_kunjungan
        $years = Eduwisata::selectRaw('YEAR(jadwal_kunjungan) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Format filter berdasarkan tahun
        $filters = [];
        foreach ($years as $year) {
            // Membuat key dan value untuk filter
            $filters[$year] = 'Tahun ' . $year;
        }

        return $filters;
    }



    protected function getType(): string
    {
        return 'bar';
    }


    public function getDescription(): ?string
    {
        return 'Jumlah kunjungan setiap bulan pada tahun ' . now()->year;
    }
}
