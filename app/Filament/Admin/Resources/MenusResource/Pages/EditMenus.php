<?php

namespace App\Filament\Admin\Resources\MenusResource\Pages;

use App\Filament\Admin\Resources\MenusResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditMenus extends EditRecord
{
    protected static string $resource = MenusResource::class;

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

        return $data;
    }
}
