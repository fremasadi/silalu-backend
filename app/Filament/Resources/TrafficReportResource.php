<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrafficReportResource\Pages;
use App\Filament\Resources\TrafficReportResource\RelationManagers;
use App\Models\TrafficReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

class TrafficReportResource extends Resource
{
    protected static ?string $model = TrafficReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Laporan Lalu Lintas';

    public static function getModelLabel(): string
    {
        return 'Laporan Lalu Lintas';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Daftar Laporan Lalu Lintas';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('traffic_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('masalah')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('foto')
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('traffic.name')
                    ->label('Nama Lokasi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('masalah'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat Pada')

                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Foto Laporan') // Tombol tanpa label (ikon saja)
                    ->modalHeading('') // Tanpa judul di modal
                    ->form([
                        \Filament\Forms\Components\Placeholder::make('Foto')
                            ->content(fn ($record) =>
                                $record->foto
                                    ? new \Illuminate\Support\HtmlString('<img src="' . asset('storage/' . $record->foto) . '" style="width: 100%; border-radius: 8px;" />')
                                    : 'Tidak ada foto'
                            )
                            ->columnSpanFull()
                            ->hiddenLabel(),
                    ]),
                Tables\Actions\EditAction::make(),
            ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrafficReports::route('/'),
            'create' => Pages\CreateTrafficReport::route('/create'),
            'edit' => Pages\EditTrafficReport::route('/{record}/edit'),
        ];
    }
}
