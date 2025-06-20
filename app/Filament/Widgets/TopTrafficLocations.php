<?php

// app/Filament/Widgets/TopTrafficLocations.php
namespace App\Filament\Widgets;

use App\Models\Traffic;
use App\Models\TrafficReport;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopTrafficLocations extends BaseWidget
{
    protected static ?string $heading = 'Lokasi dengan Laporan Terbanyak';
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Traffic::query()
                    ->withCount(['reports' => function (Builder $query) {
                        $query->where('created_at', '>=', now()->subMonth());
                    }])
                    ->orderBy('reports_count', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lokasi')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('latitude')
                    ->label('Latitude')
                    ->numeric(8),
                    
                Tables\Columns\TextColumn::make('longitude')
                    ->label('Longitude')
                    ->numeric(8),
                    
                Tables\Columns\TextColumn::make('reports_count')
                    ->label('Jumlah Laporan (30 hari)')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state > 10 => 'danger',
                        $state > 5 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('reports_count', 'desc')
            ->paginated(false);
    }
}