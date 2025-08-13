<?php

namespace App\Observers;

use App\Models\Menus;
use Illuminate\Support\Facades\Auth;

class MenusObserver
{
    /**
     * Trước khi tạo: gán vị trí tự động
     */
    public function creating(Menus $menu): void
    {
        $userId = Auth::id();
        if ($userId) {
            $menu->created_by = $menu->created_by ?: $userId;
        }
        
        // Tự động set ngày tạo và ngày sửa
        $now = now();
        $menu->created_at = $menu->created_at ?: $now;
        
        // Tự động gán vị trí nếu không có
        if (empty($menu->position) || (int) $menu->position <= 0) {
            $maxPosition = Menus::where('id_parent', $menu->id_parent)->max('position') ?? 0;
            $menu->position = $maxPosition + 1;
        }
    }

    /**
     * Trước khi cập nhật: xử lý khi thay đổi vị trí
     */
    public function updating(Menus $menu): void
    {
        if (Auth::id()) {
            $menu->updated_by = Auth::id();
        }
        
        // Tự động cập nhật ngày sửa
        $menu->updated_at = now();
        
        $oldPosition = $menu->getOriginal('position');
        $oldParentId = $menu->getOriginal('id_parent');
        $newPosition = $menu->position;
        $newParentId = $menu->id_parent;

        // Không thay đổi gì về vị trí/danh mục cha
        if ($oldPosition === $newPosition && $oldParentId === $newParentId) {
            return;
        }

        // Xử lý khi thay đổi danh mục cha
        if ($oldParentId !== $newParentId) {
            // Thu gọn khoảng trống ở danh mục cha cũ
            Menus::where('id_parent', $oldParentId)
                ->where('position', '>', $oldPosition)
                ->decrement('position');

            // Tự động gán vị trí mới nếu không hợp lệ
            $maxInNewParent = Menus::where('id_parent', $newParentId)->max('position') ?? 0;
            if ($newPosition <= 0 || $newPosition > ($maxInNewParent + 1)) {
                $menu->position = $maxInNewParent + 1;
            }
        }

        // Xử lý khi thay đổi vị trí trong cùng danh mục cha
        if ($oldParentId === $newParentId && $oldPosition !== $newPosition) {
            $maxInSameParent = Menus::where('id_parent', $newParentId)->max('position') ?? 0;
            
            if ($newPosition <= 0) {
                $newPosition = 1;
            }
            if ($newPosition > $maxInSameParent) {
                $newPosition = $maxInSameParent;
            }

            if ($newPosition === $oldPosition) {
                $menu->position = $newPosition;
                return;
            }

            if ($newPosition < $oldPosition) {
                // Kéo lên: tăng +1 các menu nằm trong [newPosition, oldPosition-1]
                Menus::where('id_parent', $newParentId)
                    ->whereBetween('position', [$newPosition, $oldPosition - 1])
                    ->increment('position');
            } else {
                // Kéo xuống: giảm -1 các menu nằm trong [oldPosition+1, newPosition]
                Menus::where('id_parent', $newParentId)
                    ->whereBetween('position', [$oldPosition + 1, $newPosition])
                    ->decrement('position');
            }

            $menu->position = $newPosition;
        }
    }

    /**
     * Sau khi xóa: thu gọn vị trí trong cùng danh mục cha
     */
    public function deleted(Menus $menu): void
    {
        Menus::where('id_parent', $menu->id_parent)
            ->where('position', '>', $menu->position)
            ->decrement('position');
    }

    // Methods để thay đổi vị trí menu
    public function canMoveUp(Menus $menu): bool
    {
        $previousMenu = $this->getPreviousMenu($menu);
        return $previousMenu !== null;
    }

    public function canMoveDown(Menus $menu): bool
    {
        $nextMenu = $this->getNextMenu($menu);
        return $nextMenu !== null;
    }

    public function moveUp(Menus $menu): void
    {
        $previousMenu = $this->getPreviousMenu($menu);
        if ($previousMenu) {
            $this->swapPosition($menu, $previousMenu);
        }
    }

    public function moveDown(Menus $menu): void
    {
        $nextMenu = $this->getNextMenu($menu);
        if ($nextMenu) {
            $this->swapPosition($menu, $nextMenu);
        }
    }

    private function getPreviousMenu(Menus $menu): ?Menus
    {
        return Menus::where('id_parent', $menu->id_parent)
            ->where('position', '<', $menu->position)
            ->orderBy('position', 'desc')
            ->first();
    }

    private function getNextMenu(Menus $menu): ?Menus
    {
        return Menus::where('id_parent', $menu->id_parent)
            ->where('position', '>', $menu->position)
            ->orderBy('position', 'asc')
            ->first();
    }

    private function swapPosition(Menus $menu1, Menus $menu2): void
    {
        $tempPosition = $menu1->position;
        $menu1->position = $menu2->position;
        $menu2->position = $tempPosition;

        $menu1->save();
        $menu2->save();
    }
} 