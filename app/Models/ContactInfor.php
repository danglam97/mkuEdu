<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContactInfor extends Model
{
    protected $table = 'contact_infor';

    protected $fillable = [
        'name',
        'addresss',
        'image',
        'type',
        'major_id',
        'faculty_institute',
        'email',
        'phone',
        'facebook',
        'zalo',
        'slug',
        'total_view',
        'link_url',
        'is_home',
        'position',
        'created_date',
        'updated_date',
        'created_by',
        'updated_by',
        'isdelete',
        'isactive',
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'updated_date' => 'datetime',
        'isdelete' => 'integer',
        'isactive' => 'integer',
        'is_home' => 'integer',
        'position' => 'integer',
        'total_view' => 'integer',
    ];

    /**
     * Thuộc chuyên ngành
     */
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    /**
     * Thuộc khoa/viện/phòng ban
     */
    public function facultyInstitute(): BelongsTo
    {
        return $this->belongsTo(Menus::class, 'faculty_institute');
    }

    /**
     * Người tạo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Người sửa
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Tên khoa/viện/phòng ban
     */
    public function getFacultyInstituteNameAttribute()
    {
        if ($this->facultyInstitute) {
            return $this->facultyInstitute->name;
        }
        return 'N/A';
    }

    /**
     * Tên chuyên ngành
     */
    public function getMajorNameAttribute()
    {
        if ($this->major) {
            return $this->major->name;
        }
        return 'N/A';
    }

    protected static function boot()
    {
        parent::boot();

        // Khi tạo mới
        static::creating(function ($model) {
            if (!$model->created_date) {
                $model->created_date = now();
            }
            if (!$model->updated_date) {
                $model->updated_date = now();
            }
            if (!$model->created_by) {
                $model->created_by = Auth::id();
            }
            if (!$model->slug) {
                $model->slug = Str::slug($model->name);
            }
            if (!$model->position || $model->position == 0) {
                $maxPosition = static::max('position') ?? 0;
                $model->position = $maxPosition + 1;
            }
        });

        // Khi cập nhật
        static::updating(function ($model) {
            $model->updated_date = now();
            $model->updated_by = Auth::id();
            if (!$model->slug) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
