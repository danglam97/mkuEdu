<?php

namespace App\Filament\Admin\Resources\MenusResource\Pages;

use App\Filament\Admin\Resources\MenusResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMenus extends CreateRecord
{
    protected static string $resource = MenusResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_date'] = Carbon::now(); // hoặc now()
        $data['created_by'] = Auth::id();      // lấy ID người dùng hiện tại

        return $data;
    }
}
