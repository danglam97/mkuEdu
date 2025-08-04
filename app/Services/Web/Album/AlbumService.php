<?php

namespace App\Services\Web\Album;

use App\Models\Album;
use App\Services\Web\Album\AlbumServiceInterface;

class AlbumService implements AlbumServiceInterface
{
    public function getAlbumImage()
    {
        $albums = Album::with(['mainImage', 'subImages'])
            ->where('isactive', 1)
            ->orderBy('position', 'asc')
            ->get();
        return $albums;
    }
}
