<?php

namespace App\Filament\Admin\Resources\CategoryNewsResource\Pages;

use App\Filament\Admin\Resources\CategoryNewsResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCategoryNews extends CreateRecord
{
    protected static string $resource = CategoryNewsResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_date'] = Carbon::now(); // hoặc now()
        $data['created_by'] = Auth::id();      // lấy ID người dùng hiện tại

        return $data;
    }

}
