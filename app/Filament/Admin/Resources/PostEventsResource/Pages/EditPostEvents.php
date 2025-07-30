<?php

namespace App\Filament\Admin\Resources\PostEventsResource\Pages;

use App\Filament\Admin\Resources\PostEventsResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPostEvents extends EditRecord
{
    protected static string $resource = PostEventsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_date'] = Carbon::now();  // hoặc now()
        $data['updated_by'] = Auth::id();       // hoặc auth()->id()
        $data['isactive'] = 0;
        return $data;
    }
}
