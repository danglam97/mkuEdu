<?php

namespace App\Services\Web\Major;

use App\Services\Web\Major\MajorServiceInterface;
use App\Models\Major;

class MajorService implements MajorServiceInterface
{
    public function getMajors()
    {
        return Major::where('is_active', 1)->orderBy('created_at', 'desc')->get();
    }
}
