<?php

namespace App\Filament\Admin\Resources\SettingResource\Pages;

use App\Filament\Admin\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public function mount(): void
    {
        $setting = \App\Models\Setting::first();

        if (!$setting) {
            $setting = \App\Models\Setting::create(); // nếu chưa có thì tạo
        }

        $this->redirect(SettingResource::getUrl('edit', ['record' => $setting]));
    }
}
