<?php

namespace App\Filament\Admin\Resources\ContactInforResource\Pages;

use App\Filament\Admin\Resources\ContactInforResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContactInfor extends ViewRecord
{
    protected static string $resource = ContactInforResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Sửa')
                ->icon('heroicon-o-pencil-square'),
        ];
    }
}
