<?php

namespace App\Filament\Admin\Resources\InternationalPostResource\Pages;

use App\Filament\Admin\Resources\InternationalPostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternationalPost extends EditRecord
{
    protected static string $resource = InternationalPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
