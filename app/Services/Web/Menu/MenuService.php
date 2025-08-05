<?php

namespace App\Services\Web\Menu;

use App\Enums\Menu\MenuIsActive;
use App\Models\Menus;
use App\Repositories\Web\Menu\MenuRepository;
use App\Services\Web\Menu\MenuServiceInterface;

class MenuService implements MenuServiceInterface
{

    public function getMenuTree($parentId = null)
    {
        return Menus::where('id_parent', $parentId)
            ->orderBy('position')
            ->with(['children' => function ($query) {
                $query->orderBy('position')
                    ->with('children');
            }])
            ->get();
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
    public function getMenuCooperate()
    {
        $menuScienceTechnology = Menus::where('is_active', MenuIsActive::Approved->value)
            ->where('slug', 'hop-tac-quoc-te')
            ->with(['children' => function ($query) {
                $query->where('is_active', MenuIsActive::Approved->value);
            }])
            ->first();
        return $menuScienceTechnology;
    }
}
