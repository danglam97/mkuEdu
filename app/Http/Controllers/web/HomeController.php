<?php

namespace App\Http\Controllers\web;

use App\Services\Web\Media\MediaService;
use App\Services\Web\Album\AlbumService;
use App\Services\Web\Menu\MenuService;
use App\Http\Controllers\Controller;
use App\Services\Web\Post\PostService;
use Illuminate\Http\Request;

class HomeController extends BaseWebController
{

    protected $postService;
    protected $mediaAlubumService;
    protected $mediaVideoService;

    public function __construct(PostService  $postService,
                                AlbumService $albumService,
                                MediaService $mediaVideoService
    )
    {
        parent::__construct(app(\App\Services\Web\Menu\MenuService::class));
        $this->postService = $postService;
        $this->albumService = $albumService;
        $this->mediaVideoService = $mediaVideoService;
    }

    public function index()
    {
        $menuTrain = app(\App\Services\Web\Menu\MenuService::class)->getMenuTrain();
        $menuScienceTechnology = app(\App\Services\Web\Menu\MenuService::class)->getMenuScienceTechnology();
        $menuCooperate = app(\App\Services\Web\Menu\MenuService::class)->getMenuCooperate();
        $postNews = $this->postService->getLatestHomePostNews(8);
        $postEvents = $this->postService->getLatestHomePostEvents(4);
        $albumMedias = $this->albumService->getAlbumImage();
        $mediaVideo = $this->mediaVideoService->getMediaVideo();
        return view('web.home', [

            'menuTrain' => $menuTrain,
            'menuScienceTechnology' => $menuScienceTechnology,
            'postNews' => $postNews,
            'postEvents' => $postEvents,
            'albumMedias' => $albumMedias,
            'mediaVideo' => $mediaVideo,
            'menuCooperate' => $menuCooperate,
        ]);
    }

    public function menuPost($slug)
    {
        return view('web.404.error');
    }
}
