<?php

namespace App\Filament\Admin\Resources\WebLinkResource\Pages;

use App\Filament\Admin\Resources\WebLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebLink extends EditRecord
{
    protected static string $resource = WebLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
