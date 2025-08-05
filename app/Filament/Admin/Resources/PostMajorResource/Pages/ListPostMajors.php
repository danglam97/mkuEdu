<?php

namespace App\Filament\Admin\Resources\PostMajorResource\Pages;

use App\Filament\Admin\Resources\PostMajorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPostMajors extends ListRecords
{
    protected static string $resource = PostMajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
