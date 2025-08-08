<?php

namespace App\Observers;

use App\Models\Banner;
use Illuminate\Support\Facades\Auth;

class BannerObserver
{
    /**
     * Trước khi tạo: gán thứ tự tự tăng trong cùng vị trí
     */
    public function creating(Banner $banner): void
    {
        $userId = Auth::id();
        if ($userId) {
            $banner->created_by = $banner->created_by ?: $userId;
        }
        
        // Tự động set ngày tạo và ngày sửa
        $now = now();
        $banner->created_at = $banner->created_at ?: $now;
        
        $currentMaxOrder = (int) (Banner::where('position', $banner->position)->max('order') ?? 0);

        if (empty($banner->order) || (int) $banner->order <= 0) {
            $banner->order = $currentMaxOrder + 1;
            return;
        }

        // Nếu order được chỉ định và nằm trong khoảng, dịch chuyển những bản ghi phía sau
        $desiredOrder = (int) $banner->order;
        if ($desiredOrder <= $currentMaxOrder) {
            Banner::where('position', $banner->position)
                ->where('order', '>=', $desiredOrder)
                ->increment('order');
        }
    }

    /**
     * Trước khi cập nhật: xử lý khi thay đổi vị trí hoặc thứ tự
     */
    public function updating(Banner $banner): void
    {
        if (Auth::id()) {
            $banner->updated_by = Auth::id();
        }
        
        // Tự động cập nhật ngày sửa
        $banner->updated_at = now();
        $oldPosition = $banner->getOriginal('position');
        $oldOrder = (int) $banner->getOriginal('order');
        $newPosition = $banner->position;
        $newOrder = (int) $banner->order;

        // Không thay đổi gì về vị trí/thứ tự
        if ($oldPosition === $newPosition && $oldOrder === $newOrder) {
            return;
        }

        // Di chuyển sang vị trí mới
        if ($oldPosition !== $newPosition) {
            // Thu gọn khoảng trống ở vị trí cũ
            Banner::where('position', $oldPosition)
                ->where('order', '>', $oldOrder)
                ->decrement('order');

            $maxInNewPosition = (int) (Banner::where('position', $newPosition)->max('order') ?? 0);

            if ($newOrder <= 0 || $newOrder > ($maxInNewPosition + 1)) {
                // Gắn về cuối danh sách vị trí mới
                $banner->order = $maxInNewPosition + 1;
            } else {
                // Chèn vào giữa: đẩy các bản ghi sau lên 1
                Banner::where('position', $newPosition)
                    ->where('order', '>=', $newOrder)
                    ->increment('order');
                $banner->order = $newOrder;
            }

            return;
        }

        // Cùng vị trí: thay đổi thứ tự
        $maxInSamePosition = (int) (Banner::where('position', $newPosition)->max('order') ?? 0);
        if ($newOrder <= 0) {
            $newOrder = 1;
        }
        if ($newOrder > $maxInSamePosition) {
            $newOrder = $maxInSamePosition;
        }

        if ($newOrder === $oldOrder) {
            $banner->order = $newOrder;
            return;
        }

        if ($newOrder < $oldOrder) {
            // Kéo lên: tăng +1 các bản ghi nằm trong [newOrder, oldOrder-1]
            Banner::where('position', $newPosition)
                ->whereBetween('order', [$newOrder, $oldOrder - 1])
                ->increment('order');
        } else {
            // Kéo xuống: giảm -1 các bản ghi nằm trong [oldOrder+1, newOrder]
            Banner::where('position', $newPosition)
                ->whereBetween('order', [$oldOrder + 1, $newOrder])
                ->decrement('order');
        }

        $banner->order = $newOrder;
    }

    /**
     * Sau khi xóa: thu gọn thứ tự trong cùng vị trí
     */
    public function deleted(Banner $banner): void
    {
        Banner::where('position', $banner->position)
            ->where('order', '>', $banner->order)
            ->decrement('order');
    }
} 