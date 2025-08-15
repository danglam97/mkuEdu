<?php

namespace App\Filament\Admin\Resources\AlbumResource\Pages;

use App\Filament\Admin\Resources\AlbumResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAlbum extends CreateRecord
{
    protected static string $resource = AlbumResource::class;
    
    protected array $subImages = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Lưu sub_images để sử dụng sau
        $this->subImages = $data['sub_images'] ?? [];
        
        // Loại bỏ sub_images khỏi data chính
        unset($data['sub_images']);
        
        return $data;
    }

    protected function afterCreate(): void
    {
        // Xử lý ảnh phụ sau khi tạo album
        $subImages = $this->subImages ?? [];
        
        if (is_array($subImages) && !empty($subImages)) {
            // Tạo album items cho ảnh phụ
            foreach ($subImages as $index => $image) {
                $this->record->items()->create([
                    'image' => $image,
                    'type' => 'sub',
                    'order' => (int)$index + 1, // Bắt đầu từ 1
                ]);
            }
        }
    }
}
