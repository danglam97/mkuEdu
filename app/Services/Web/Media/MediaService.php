<?php

namespace App\Services\Web\Media;

use App\Models\Media;
use App\Services\Web\Media\MediaServiceInterface;

class MediaService implements MediaServiceInterface
{
    public function getMediaVideo()
    {
        return Media::where('type', 'image')
            ->latest()
            ->first();
    }
}
