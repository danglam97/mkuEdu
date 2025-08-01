<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
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
    ];

    public static function booted()
    {
        static::saving(function ($post) {
            $post->slug = Str::slug($post->name);
        });
    }

    public function category()
    {
        return $this->belongsTo(Menus::class, 'id_category');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function approverBy()
    {
        return $this->belongsTo(User::class, 'approver_by');
    }

    public function approvals()
    {
        return $this->hasMany(PostApproval::class);
    }

    public function latestApproval()
    {
        return $this->hasOne(PostApproval::class)->latestOfMany();
    }
}
