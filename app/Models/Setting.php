<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Setting extends Model
{
    protected $table = 'web_settings';

    protected $fillable = [
        'name_uni',
        'name_sologan', 
        'description',
        'logo',
        'favicon',
        'email',
        'phone',
        'address',
        'link_url',
        'created_by',
        'updated_by',
        'isdelete',
        'isactive',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'isdelete' => 'integer',
        'isactive' => 'integer',
    ];

    /**
     * Người tạo setting
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Người sửa setting
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
            if (!$model->created_at) {
                $model->created_at = now();
            }
            if (!$model->updated_at) {
                $model->updated_at = now();
            }
            if (!$model->created_by) {
                $model->created_by = Auth::id();
            }
        });

        // Khi cập nhật
        static::updating(function ($model) {
            $model->updated_at = now();
            $model->updated_by = Auth::id();
        });
    }
}
