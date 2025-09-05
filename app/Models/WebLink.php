<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class WebLink extends Model
{
    protected $table = 'web_links';

    protected $fillable = [
        'name',
        'code',
        'image',
        'type',
        'faculty_institute',
        'contents',
        'total_view',
        'link_url',
        'is_home',
        'position',
        'isdelete',
        'isactive',
        'created_date',
        'updated_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'updated_date' => 'datetime',
        'isactive'     => 'boolean',
        'is_home'      => 'boolean',
        'isdelete'     => 'boolean',
        'total_view'   => 'integer',
        'position'     => 'integer',
    ];

    /**
     * Người tạo liên kết
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Người sửa liên kết
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function boot()
    {
        parent::boot();

        // Khi tạo mới
        static::creating(function ($model) {
            $model->created_date = Carbon::now();
            $model->created_by = Auth::id();
        });

        // Khi cập nhật
        static::updating(function ($model) {
            $model->updated_date = Carbon::now();
            $model->updated_by = Auth::id();
        });
    }
}
