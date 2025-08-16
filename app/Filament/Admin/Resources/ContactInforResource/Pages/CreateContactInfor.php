<?php

namespace App\Filament\Admin\Resources\ContactInforResource\Pages;

use App\Filament\Admin\Resources\ContactInforResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContactInfor extends CreateRecord
{
    protected static string $resource = ContactInforResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
