<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'image',
        'link',
        'position',
        'order',
        'description',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope để lấy banner theo vị trí và sắp xếp theo thứ tự
     */
    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position)
                    ->where('is_active', true)
                    ->orderBy('order');
    }

    /**
     * Scope để lấy banner đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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
