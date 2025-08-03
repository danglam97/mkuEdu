<?php

namespace App\Http\Controllers\web;

use App\Services\Web\Menu\MenuService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index()
    {
        $menus = $this->menuService->getMenuTree();
        $menuTrain = $this->menuService->getMenuTrain();
        $menuScienceTechnology = $this->menuService->getMenuScienceTechnology();
        return view('web.home', [
            'menus' => $menus,
            'menuTrain' => $menuTrain,
            'menuScienceTechnology' => $menuScienceTechnology,
        ]);
    }

    public function menuPost($slug)
    {
        return view('web.404.error');
    }
}
