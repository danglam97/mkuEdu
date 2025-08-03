<?php

namespace App\Services\Web\Menu;

use App\Enums\Menu\MenuIsActive;
use App\Models\Menus;
use App\Repositories\Web\Menu\MenuRepository;
use App\Services\Web\Menu\MenuServiceInterface;

class MenuService implements MenuServiceInterface
{

    public function getMenuTree()
    {

        // Lấy tất cả menu đã duyệt
        $menus = Menus::where('is_active', MenuIsActive::Approved->value)
            ->orderBy('position')
            ->orderBy('name')
            ->get(['id', 'id_parent', 'name', 'slug', 'type']);;
        return $this->buildTree($menus);
    }

    private function buildTree($menus, $parentId = null)
    {
        return $menus->where('id_parent', $parentId)
            ->map(function ($menu) use ($menus) {
                $menu->children = $this->buildTree($menus, $menu->id);
                return $menu;
            })->values();
    }

    public function getMenuTrain()
    {
        $menuTrain = Menus::where('is_active', MenuIsActive::Approved->value)
            ->where('slug', 'dao-tao')
            ->with(['children' => function ($query) {
                $query->where('is_active', MenuIsActive::Approved->value);
            }])
            ->first();
        return $menuTrain;
    }
    public function getMenuScienceTechnology()
    {
        $menuScienceTechnology = Menus::where('is_active', MenuIsActive::Approved->value)
            ->where('slug', 'khoa-hoc-cong-nghe')
            ->with(['children' => function ($query) {
                $query->where('is_active', MenuIsActive::Approved->value);
            }])
            ->first();
        return $menuScienceTechnology;
    }
}
