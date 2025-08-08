<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Major extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'icon',
        'created_by',
        'updated_by',
        'slug'

    ];
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function postMajors()
    {
        return $this->hasMany(PostMajor::class);
    }
    public static function booted()
    {
        static::saving(function ($post) {
            $post->slug = Str::slug($post->name);
        });
    }
}
