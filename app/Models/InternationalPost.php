<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Str;

class InternationalPost extends Model
{
    protected $table = 'international_posts';
    
    protected $fillable = [
        'name',
        'description',
        'image',
        'id_category',
        'slug_category',
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
        'status',
    ];

    protected $casts = [
        'is_home' => 'boolean',
        'isdelete' => 'boolean',
        'isactive' => 'boolean',
        'total_view' => 'integer',
        'id_category' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'approver_by' => 'integer',
        'status' => 'integer',
        'created_date' => 'datetime',
        'updated_date' => 'datetime',
    ];

    /**
     * Quan hệ với người tạo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Quan hệ với người sửa
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function booted()
    {
        static::saving(function ($post) {
            $post->slug = Str::slug($post->name);
        });
    }
}
