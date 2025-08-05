<?php

namespace App\Http\Controllers\web;

use App\Services\Web\Media\MediaService;
use App\Services\Web\Album\AlbumService;
use App\Services\Web\Menu\MenuService;
use App\Http\Controllers\Controller;
use App\Services\Web\Post\PostService;
use App\Services\Web\Major\MajorService;
use Illuminate\Http\Request;

class HomeController extends BaseWebController
{

    protected $postService;
    protected $mediaAlubumService;
    protected $mediaVideoService;
    protected $majorService;
    protected $albumService;
    public function __construct(PostService  $postService,
                                AlbumService $albumService,
                                MediaService $mediaVideoService,
                                MajorService $majorService
    )
    {
        parent::__construct(app(\App\Services\Web\Menu\MenuService::class));
        $this->postService = $postService;
        $this->albumService = $albumService;
        $this->mediaVideoService = $mediaVideoService;
        $this->majorService = $majorService;
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
        $majors = $this->majorService->getMajors();
        return view('web.home', [

            'menuTrain' => $menuTrain,
            'menuScienceTechnology' => $menuScienceTechnology,
            'postNews' => $postNews,
            'postEvents' => $postEvents,
            'albumMedias' => $albumMedias,
            'mediaVideo' => $mediaVideo,
            'menuCooperate' => $menuCooperate,
            'majors' => $majors,
        ]);
    }

    public function menuPost($slug)
    {
        return view('web.404.error');
    }
    public function postEvent (){
        return view('web.post_event.detail');
    }
    public function detailPostEvent ($slug){
        return view('web.post_event.detail');
    }
    public function category (){
        return view('web.post_event.detail');
    }
}
