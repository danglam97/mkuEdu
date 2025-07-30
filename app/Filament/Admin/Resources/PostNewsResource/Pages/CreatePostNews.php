<?php

namespace App\Filament\Admin\Resources\PostNewsResource\Pages;

use App\Filament\Admin\Resources\PostNewsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePostNews extends CreateRecord
{
    protected static string $resource = PostNewsResource::class;
}
