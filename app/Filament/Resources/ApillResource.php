<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApillResource\Pages;
use App\Models\Apill;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ApillResource extends Resource
{
    protected static ?string $model = Apill::class;

    protected static ?string $navigationIcon = 'heroicon-o-traffic-light';

    protected static ?string $navigationGroup = 'Manajemen Lalu Lintas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama APILL')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama APILL')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y - H:i'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApills::route('/'),
            'create' => Pages\CreateApill::route('/create'),
            'edit' => Pages\EditApill::route('/{record}/edit'),
        ];
    }
}
