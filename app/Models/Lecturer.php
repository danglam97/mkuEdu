<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lecturer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'position',
        'major_id',
        'description',
        'isactive',
        'created_date',
        'updated_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'updated_date' => 'datetime',
    ];

    // Relationships
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Events
    protected static function booted()
    {
        static::creating(function ($lecturer) {
            $lecturer->created_date = now();
            
            // Tự động lấy ID của user đang đăng nhập
            if (auth()->check()) {
                $lecturer->created_by = auth()->id();
            }
        });

        static::updating(function ($lecturer) {
            $lecturer->updated_date = now();
            
            // Tự động lấy ID của user đang đăng nhập khi cập nhật
            if (auth()->check()) {
                $lecturer->updated_by = auth()->id();
            }
        });
    }
}
