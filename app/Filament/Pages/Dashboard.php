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

