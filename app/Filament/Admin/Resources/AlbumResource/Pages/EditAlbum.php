<?php

namespace App\Filament\Admin\Resources\AlbumResource\Pages;

use App\Filament\Admin\Resources\AlbumResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlbum extends EditRecord
{
    protected static string $resource = AlbumResource::class;
    
    protected array $subImages = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Lưu sub_images để sử dụng sau
        $this->subImages = $data['sub_images'] ?? [];
        
        // Loại bỏ sub_images khỏi data chính
        unset($data['sub_images']);
        
        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load ảnh phụ hiện tại để hiển thị trong form
        $subImages = $this->record->items()
            ->where('type', 'sub')
            ->orderBy('order')
            ->pluck('image')
            ->toArray();
        
        $data['sub_images'] = $subImages;
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Xử lý ảnh phụ sau khi cập nhật album
        $subImages = $this->subImages ?? [];
        
        // Chỉ xử lý nếu có ảnh mới được upload
        if (is_array($subImages) && !empty($subImages)) {
            // Xóa ảnh phụ cũ
            $this->record->items()->where('type', 'sub')->delete();
            
            // Tạo album items mới cho ảnh phụ
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
