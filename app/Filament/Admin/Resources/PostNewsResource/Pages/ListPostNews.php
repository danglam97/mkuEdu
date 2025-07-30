<?php

namespace App\Filament\Admin\Resources\PostNewsResource\Pages;

use App\Filament\Admin\Resources\PostNewsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPostNews extends ListRecords
{
    protected static string $resource = PostNewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
