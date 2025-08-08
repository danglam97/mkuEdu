<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class PostMajor extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'id_category',
        'contents',
        'total_view',
        'link_url',
        'is_home',
        'slug',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'created_date',
        'updated_date',
        'created_by',
        'updated_by',
        'approver_by',
        'isdelete',
        'isactive',
        'status'
    ];

    public function major()
    {
        return $this->belongsTo(Major::class,'id_category');
    }
    public static function booted()
    {
        static::saving(function ($post) {
            $post->slug = Str::slug($post->name);
        });
    }
}
