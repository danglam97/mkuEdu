<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'medias';
    protected $fillable = [
        'title',
        'type',
        'source',
        'url',
        'thumbnail',
        'page',
        'position',
        'description',
    ];

}
