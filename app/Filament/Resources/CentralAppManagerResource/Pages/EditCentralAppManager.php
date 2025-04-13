<?php

namespace App\Filament\Resources\CentralAppManagerResource\Pages;

use App\Filament\Resources\CentralAppManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentralAppManager extends EditRecord
{
    protected static string $resource = CentralAppManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
