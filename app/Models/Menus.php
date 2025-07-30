<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Menus extends Model
{
 protected $table = 'menus';
    protected $fillable = [
        'name',
        'notes',
        'icon',
        'id_parent',
        'type',
        'slug',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'created_date',
        'updated_date',
        'created_by',
        'updated_by',
        'is_active',
        'position',
    ];
    public static function booted()
    {
        static::saving(function ($menu) {
            $menu->slug = Str::slug($menu->name);
        });
    }

    public function parent()
    {
        return $this->belongsTo(__CLASS__, 'id_parent');
    }

    public function children()
    {
        return $this->hasMany(__CLASS__, 'id_parent');
    }
    public static function groupedCategories(): array
    {
        // 1 query duy nhất
        $categories = self::all()->groupBy('id_parent');

        $result = [];
        self::loopCategories(null, $categories, $result);

        return $result;
    }

    protected static function loopCategories($parentId, $grouped, &$result, $prefix = '')
    {
        if (!isset($grouped[$parentId])) return;

        foreach ($grouped[$parentId] as $menu) {
            $result[$menu->id] = $prefix . $menu->name;
            self::loopCategories($menu->id, $grouped, $result, $prefix . '-- ');
        }
    }
    public function canBeDeleted(): bool
    {
        return $this->children()->count() === 0;
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
