<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ['title', 'page', 'position','isactive'];

    public function mainImage()
    {
        return $this->hasOne(AlbumItem::class)->where('type', 'main');
    }

    public function subImages()
    {
        return $this->hasMany(AlbumItem::class)->where('type', 'sub')->orderBy('order');
    }

    public function items()
    {
        return $this->hasMany(AlbumItem::class);
    }
    protected static function booted()
    {
        static::creating(function ($album) {
            if (empty($album->position)) {
                $maxPosition = self::max('position') ?? 0;
                $album->position = $maxPosition + 1;
            }

            if (empty($album->page)) {
                $album->page = 'home';
            }
        });
        static::deleting(function ($album) {
            // Xóa file ảnh chính
            if ($album->mainImage && $album->mainImage->image) {
                $filePath = public_path($album->mainImage->image);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            // Xóa file ảnh phụ
            foreach ($album->items as $item) {
                if ($item->image) {
                    $filePath = public_path($item->image);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }
        });
    }
}

