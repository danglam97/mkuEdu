<?php

namespace App\Helpers;

use App\Models\Menus;

class MenuHelper
{
    /**
     * Lấy danh sách Khoa/Viện/Phòng ban dạng cây
     *
     * @return array
     */
    public static function getFacultyInstituteOptions(): array
    {
        $options = [];
        
        $slugs = ['cac-phong-ban', 'cac-khoa', 'cac-vien', 'cac-trung-tam'];
        
        foreach ($slugs as $slug) {
            // Lấy record đầu tiên với slug này
            $menu = Menus::where('slug', $slug)->first();
            
            if ($menu) {
                $groupOptions = [];
                
                // Lấy các menu con
                $children = Menus::where('id_parent', $menu->id)
                    ->where('is_active', 1)
                    ->orderBy('position')
                    ->get();
                
                if ($children->count() > 0) {
                    foreach ($children as $child) {
                        if ($child && $child->name) {
                            $groupOptions[$child->id] = $child->name;
                        }
                    }
                }
                
                // Tạo optgroup với tên menu cha
                $options[$menu->name] = $groupOptions;
            }
        }
        
        return $options;
    }

    /**
     * Lấy danh sách Khoa/Viện/Phòng ban dạng phẳng (không có optgroup)
     *
     * @return array
     */
    public static function getFacultyInstituteFlatOptions(): array
    {
        $options = [];
        
        $slugs = ['cac-phong-ban', 'cac-khoa', 'cac-vien', 'cac-trung-tam'];
        
        foreach ($slugs as $slug) {
            // Lấy record đầu tiên với slug này
            $menu = Menus::where('slug', $slug)->first();
            
            if ($menu) {
                // Thêm menu cha
                $options[$menu->id] = $menu->name;
                
                // Lấy các menu con
                $children = Menus::where('id_parent', $menu->id)
                    ->where('is_active', 1)
                    ->orderBy('position')
                    ->get();
                
                if ($children->count() > 0) {
                    foreach ($children as $child) {
                        if ($child && $child->name) {
                            $options[$child->id] = $child->name;
                        }
                    }
                }
            }
        }
        
        return $options;
    }

    /**
     * Lấy danh sách menu cha (chỉ có các nhóm chính)
     *
     * @return array
     */
    public static function getParentMenuOptions(): array
    {
        $options = [];
        
        $slugs = ['cac-phong-ban', 'cac-khoa', 'cac-vien', 'cac-trung-tam'];
        
        foreach ($slugs as $slug) {
            $menu = Menus::where('slug', $slug)->first();
            
            if ($menu) {
                $options[$menu->id] = $menu->name;
            }
        }
        
        return $options;
    }
}
