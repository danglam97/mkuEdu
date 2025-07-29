<?php

namespace App\Filament\Admin\Resources\CategoryNewsResource\Pages;

use App\Filament\Admin\Resources\CategoryNewsResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditCategoryNews extends EditRecord
{
    protected static string $resource = CategoryNewsResource::class;

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
