<?php

namespace App\Filament\Resources\TrafficReportResource\Pages;

use App\Filament\Resources\TrafficReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrafficReports extends ListRecords
{
    protected static string $resource = TrafficReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
