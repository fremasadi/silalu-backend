<?php

// app/Filament/Resources/TrafficResource/Pages/ViewTraffic.php

namespace App\Filament\Resources\TrafficResource\Pages;

use App\Filament\Resources\TrafficResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTraffic extends ViewRecord
{
    protected static string $resource = TrafficResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}