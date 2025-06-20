<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget as BaseStatsWidget;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\TableWidget as BaseTableWidget;
use App\Models\User;
use App\Models\Traffic;
use App\Models\TrafficReport;
use Illuminate\Support\Facades\DB;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $title = 'Dashboard Admin';
    protected static ?string $navigationLabel = 'Dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
            ReportChart::class,
            RecentReports::class,
        ];
    }
}

class RecentReports extends BaseTableWidget
{
    protected static ?string $heading = 'Laporan Terbaru';
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TrafficReport::query()
                    ->with(['traffic', 'confirmedBy'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->size(50)
                    ->circular(),
                
                TextColumn::make('traffic.name')
                    ->label('Lokasi')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('masalah')
                    ->label('Masalah')
                    ->limit(50)
                    ->searchable(),
                
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => fn ($state): bool => $state === 'pending',
                        'success' => fn ($state): bool => $state === 'confirmed',
                        'danger' => fn ($state): bool => $state === 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'confirmed' => 'Dikonfirmasi',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),
                
                TextColumn::make('confirmedBy.name')
                    ->label('Dikonfirmasi Oleh')
                    ->default('-'),
                
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }
}


class ReportChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Laporan Bulanan';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = TrafficReport::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count'),
            'status'
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month', 'status')
        ->orderBy('month')
        ->get();

        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ags',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $pending = array_fill(1, 12, 0);
        $confirmed = array_fill(1, 12, 0);
        $rejected = array_fill(1, 12, 0);

        foreach ($data as $item) {
            if ($item->status === 'pending') {
                $pending[$item->month] = $item->count;
            } elseif ($item->status === 'confirmed') {
                $confirmed[$item->month] = $item->count;
            } elseif ($item->status === 'rejected') {
                $rejected[$item->month] = $item->count;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pending',
                    'data' => array_values($pending),
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#f59e0b',
                ],
                [
                    'label' => 'Dikonfirmasi',
                    'data' => array_values($confirmed),
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#10b981',
                ],
                [
                    'label' => 'Ditolak',
                    'data' => array_values($rejected),
                    'backgroundColor' => '#ef4444',
                    'borderColor' => '#ef4444',
                ],
            ],
            'labels' => array_values($months),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
