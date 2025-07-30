<?php

namespace App\Filament\Admin\Resources\PostEventsResource\Pages;

use App\Filament\Admin\Resources\PostEventsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPostEvents extends EditRecord
{
    protected static string $resource = PostEventsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
