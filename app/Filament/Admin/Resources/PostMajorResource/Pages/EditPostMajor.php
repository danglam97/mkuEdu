<?php

namespace App\Filament\Admin\Resources\PostMajorResource\Pages;

use App\Enums\Post\PostIsActive;
use App\Enums\Post\PostStatus;
use App\Filament\Admin\Resources\PostMajorResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPostMajor extends EditRecord
{
    protected static string $resource = PostMajorResource::class;

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
        $data['isactive'] = PostIsActive::Pending->value;

        if ($this->record->status == PostStatus::Rejected->value) {
            $data['status'] = PostStatus::Waiting->value;
        }
        if ($this->record->status == PostStatus::Approved->value) {
            $data['status'] = PostStatus::Pending->value;
        }
        return $data;
    }
}
