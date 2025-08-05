<?php

namespace App\Filament\Admin\Resources\PostMajorResource\Pages;

use App\Enums\Post\PostIsActive;
use App\Enums\Post\PostStatus;
use App\Filament\Admin\Resources\PostMajorResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePostMajor extends CreateRecord
{
    protected static string $resource = PostMajorResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_date'] = Carbon::now(); // hoặc now()
        $data['created_by'] = Auth::id();      // lấy ID người dùng hiện tại
        $data['isactive'] = PostIsActive::Pending->value;
        $data['status'] = PostStatus::Pending->value;
        return $data;
    }
}
