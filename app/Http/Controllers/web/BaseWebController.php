<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Services\Web\Menu\MenuService;

class BaseWebController extends Controller
{
    public function __construct(MenuService $menuService)
    {
        // Lấy menus
        $menus = $menuService->getMenuTree();

        // Lấy settings (ví dụ bảng settings chỉ có 1 bản ghi)
//        $settings = Setting::first();

        // Chia sẻ cho tất cả view
        view()->share([
            'menus'    => $menus,
//            'settings' => $settings
        ]);
    }

}
