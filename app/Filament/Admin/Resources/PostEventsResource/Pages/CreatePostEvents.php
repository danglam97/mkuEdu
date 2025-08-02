<?php

namespace App\Filament\Admin\Resources\PostEventsResource\Pages;

use App\Enums\Post\PostIsActive;
use App\Filament\Admin\Resources\PostEventsResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePostEvents extends CreateRecord
{
    protected static string $resource = PostEventsResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_date'] = Carbon::now(); // hoặc now()
        $data['created_by'] = Auth::id();      // lấy ID người dùng hiện tại
        $data['isactive'] = PostIsActive::Pending->value;
        return $data;
    }
}
