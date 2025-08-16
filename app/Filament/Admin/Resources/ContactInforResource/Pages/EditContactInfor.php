<?php

namespace App\Filament\Admin\Resources\ContactInforResource\Pages;

use App\Filament\Admin\Resources\ContactInforResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContactInfor extends EditRecord
{
    protected static string $resource = ContactInforResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Xóa')
                ->icon('heroicon-o-trash'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
