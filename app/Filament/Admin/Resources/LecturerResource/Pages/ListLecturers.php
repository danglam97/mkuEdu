<?php

namespace App\Filament\Admin\Resources\LecturerResource\Pages;

use App\Filament\Admin\Resources\LecturerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLecturers extends ListRecords
{
    protected static string $resource = LecturerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
