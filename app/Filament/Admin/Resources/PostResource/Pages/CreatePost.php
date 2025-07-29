<?php

namespace App\Filament\Admin\Resources\PostResource\Pages;

use App\Filament\Admin\Resources\PostResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_date'] = Carbon::now(); // hoặc now()
        $data['created_by'] = Auth::id();      // lấy ID người dùng hiện tại
        $data['isactive'] = 0;
        return $data;
    }
}
