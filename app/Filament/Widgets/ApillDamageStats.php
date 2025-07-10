<?php

namespace App\Filament\Widgets;

use App\Models\TrafficReport;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ApillDamageStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Kerusakan APILL 3 Lampu', $this->countDamagedApill('3 lampu'))
                ->description('Total laporan untuk APILL 3 lampu (pending/proses)')
                ->color('danger'),

            Card::make('Kerusakan APILL 2 Lampu', $this->countDamagedApill('2 lampu'))
                ->description('Total laporan untuk APILL 2 lampu (pending/proses)')
                ->color('warning'),

            Card::make('Kerusakan APILL 1 Lampu', $this->countDamagedApill('1 lampu'))
                ->description('Total laporan untuk APILL 1 lampu (pending/proses)')
                ->color('info'),
        ];
    }

    protected function countDamagedApill(string $jenis): int
    {
        return TrafficReport::whereIn('status', ['pending', 'proses']) // ✅ Tambah filter status
            ->whereHas('traffic', function ($query) use ($jenis) {
                $query->where('jenis_apill', $jenis);
            })
            ->count();
    }
}
