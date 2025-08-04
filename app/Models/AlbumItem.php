<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlbumItem extends Model
{
    protected $fillable = ['album_id', 'image', 'type', 'order'];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
