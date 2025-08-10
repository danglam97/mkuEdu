<?php

namespace App\Filament\Admin\Resources\WebLinkResource\Pages;

use App\Filament\Admin\Resources\WebLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebLinks extends ListRecords
{
    protected static string $resource = WebLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
