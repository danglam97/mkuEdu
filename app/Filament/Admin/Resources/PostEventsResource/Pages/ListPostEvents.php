<?php

namespace App\Filament\Admin\Resources\PostEventsResource\Pages;

use App\Filament\Admin\Resources\PostEventsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPostEvents extends ListRecords
{
    protected static string $resource = PostEventsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
