<?php

namespace App\Filament\Resources\TrafficReportResource\Pages;

use App\Filament\Resources\TrafficReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrafficReport extends EditRecord
{
    protected static string $resource = TrafficReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
