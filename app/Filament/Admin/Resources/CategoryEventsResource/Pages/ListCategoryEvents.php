<?php

namespace App\Filament\Admin\Resources\CategoryEventsResource\Pages;

use App\Filament\Admin\Resources\CategoryEventsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryEvents extends ListRecords
{
    protected static string $resource = CategoryEventsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
