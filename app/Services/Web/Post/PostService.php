<?php

namespace App\Services\Web\Post;

use App\Models\Post;
use App\Models\PostEvents;
use App\Models\PostNews;
use App\Services\Web\Post\PostServiceInterface;
use Carbon\Carbon;

class PostService implements PostServiceInterface
{
    public function __construct()
    {

    }
    public function getLatestHomePostNews($limit = 8)
    {
        $postNews = PostNews::with(['category:id,name,slug'])
            ->select('id', 'name', 'slug', 'image', 'created_at', 'id_category')
            ->where('status', 1)
            ->where('isactive', 1)
            ->where('is_home', 1)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
        if ($postNews->count() > 0) {
            $totalDays = Carbon::now()->diffInDays(Carbon::create(2025, 1, 1));
            $highlightIndex = floor(($totalDays / 2) % $postNews->count()); // 2 ngày đổi 1 lần

            if ($postNews->count() > 1) {
                $highlightPost = $postNews->splice($highlightIndex, 1)->first();
                $postNews->prepend($highlightPost);
            }
        }
        return $postNews;

    }
    public function getLatestHomePostEvents($limit = 8)
    {
        $postEvents = PostEvents::with(['category:id,name,slug'])
            ->select('id', 'name', 'slug', 'image', 'created_at', 'id_category')
            ->where('status', 1)
            ->where('isactive', 1)
            ->where('is_home', 1)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
        if ($postEvents->count() > 0) {
            $totalDays = Carbon::now()->diffInDays(Carbon::create(2025, 1, 1));
            $highlightIndex = floor(($totalDays / 2) % $postEvents->count()); // 2 ngày đổi 1 lần

            if ($postEvents->count() > 1) {
                $highlightPost = $postEvents->splice($highlightIndex, 1)->first();
                $postEvents->prepend($highlightPost);
            }
        }
        return $postEvents;

    }
}
