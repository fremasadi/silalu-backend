<?php

namespace App\Filament\Resources\TrafficResource\Pages;

use App\Filament\Resources\TrafficResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTraffic extends ListRecords
{
    protected static string $resource = TrafficResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
