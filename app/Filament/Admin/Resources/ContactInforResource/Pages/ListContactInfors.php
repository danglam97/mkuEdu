<?php

namespace App\Filament\Admin\Resources\ContactInforResource\Pages;

use App\Filament\Admin\Resources\ContactInforResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactInfors extends ListRecords
{
    protected static string $resource = ContactInforResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Thêm thông tin liên hệ')
                ->icon('heroicon-o-plus'),
        ];
    }
}
