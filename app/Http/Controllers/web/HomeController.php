<?php

namespace App\Http\Controllers\web;

use App\Services\Web\Menu\MenuService;
use App\Http\Controllers\Controller;
use App\Services\Web\Post\PostService;
use Illuminate\Http\Request;

class HomeController extends BaseWebController
{

    protected $postService;

    public function __construct( PostService $postService)
    {
        parent::__construct(app(\App\Services\Web\Menu\MenuService::class));
        $this->postService = $postService;
    }

    public function index()
    {
        $menuTrain = app(\App\Services\Web\Menu\MenuService::class)->getMenuTrain();
        $menuScienceTechnology = app(\App\Services\Web\Menu\MenuService::class)->getMenuScienceTechnology();
        $postNews = $this->postService->getLatestHomePostNews(8);
        $postEvents = $this->postService->getLatestHomePostEvents(4);
        return view('web.home', [

            'menuTrain' => $menuTrain,
            'menuScienceTechnology' => $menuScienceTechnology,
            'postNews' => $postNews,
            'postEvents' => $postEvents,
        ]);
    }

    public function menuPost($slug)
    {
        return view('web.404.error');
    }
}
