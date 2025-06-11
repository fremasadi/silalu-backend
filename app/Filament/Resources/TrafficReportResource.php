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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                Select::make('traffic_id')
                    ->label('Lokasi')
                    ->relationship('traffic', 'name')
                    ->required(),
    
                Textarea::make('masalah')
                    ->label('Masalah')
                    ->required()
                    ->columnSpanFull(),
    
                // Jika sedang edit data dan ada file foto, tampilkan preview
                Placeholder::make('preview_foto')
                    ->label('Foto Sebelumnya')
                    ->content(function ($record) {
                        if ($record && $record->foto) {
                            return new \Illuminate\Support\HtmlString(
                                '<img src="' . asset('storage/' . $record->foto) . '" style="max-width: 100%; border-radius: 8px;" />'
                            );
                        }
                        return 'Belum ada foto';
                    })
                    ->visible(fn ($record) => $record !== null)
                    ->columnSpanFull(),
    
                // Hanya tampilkan input upload saat create, bukan edit
                FileUpload::make('foto')
                    ->label('Upload Foto Baru')
                    ->directory('traffic_reports')
                    ->image()
                    ->imagePreviewHeight('200')
                    ->maxSize(2048)
                    ->visible(fn ($record) => $record === null), // Hanya saat create
    
                    Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Tertunda',
                        'proses' => 'Diproses',
                        'selesai' => 'Selesai',
                    ])
                    ->default('pending')
                    ->required()
                    ->visible(fn ($record) => $record && $record->status === 'proses'),
                

                // Tampilkan teks biasa jika status completed
                Placeholder::make('status_display')
                    ->label('Status')
                    ->content(fn ($record) => match ($record?->status) {
                        'pending' => 'Tertunda',
                        // 'progress' => 'Diproses', 
                        'selesai' => 'Selesai',
                        default => '-',
                    })
                    ->visible(fn ($record) => $record && $record->status === 'selesai'),
                                
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
                Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->formatStateUsing(function ($state) {
                    return match ($state) {
                        'pending' => 'Tertunda',
                        'progress' => 'Diproses',
                        'selesai' => 'Selesai',
                        default => $state,
                    };
                }),
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
                    ->label('Foto Laporan')
                    ->modalHeading('') // Tanpa judul di modal
                    ->form([
                        \Filament\Forms\Components\Placeholder::make('Foto Laporan')
                            ->content(fn ($record) =>
                                $record->foto
                                    ? new \Illuminate\Support\HtmlString('<img src="' . asset('storage/' . $record->foto) . '" style="width: 100%; border-radius: 8px;" />')
                                    : 'Tidak ada foto'
                            )
                            ->columnSpanFull()
                            ->hiddenLabel(),
            
                        \Filament\Forms\Components\Placeholder::make('Dikonfirmasi Oleh')
                            ->content(fn ($record) =>
                                $record->confirmed_by
                                    ? optional($record->confirmedBy)->name ?? 'Tidak diketahui'
                                    : 'Belum dikonfirmasi'
                            ),
            
                        \Filament\Forms\Components\Placeholder::make('Bukti Konfirmasi')
                            ->content(fn ($record) =>
                                $record->bukti_konfirmasi
                                    ? new \Illuminate\Support\HtmlString('<img src="' . asset('storage/' . $record->bukti_konfirmasi) . '" style="width: 100%; border-radius: 8px;" />')
                                    : 'Tidak ada bukti konfirmasi'
                            )
                            ->columnSpanFull(),
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
