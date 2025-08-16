<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Major extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'code',
        'description',
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
        'created_by_id',
        'updated_by_id',
        'slug'
    ];

    protected $casts = [
        'is_home' => 'boolean',
        'isdelete' => 'boolean',
        'isactive' => 'boolean',
        'created_date' => 'datetime',
        'updated_date' => 'datetime',
        'total_view' => 'integer',
        'position' => 'integer'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function facultyInstitute()
    {
        return $this->belongsTo(Menus::class, 'faculty_institute');
    }

    public function getFacultyInstituteFullNameAttribute()
    {
        if ($this->facultyInstitute) {
            return $this->facultyInstitute->name;
        }
        return 'N/A';
    }

    public function postMajors()
    {
        return $this->hasMany(PostMajor::class);
    }

    public static function booted()
    {
        static::saving(function ($major) {
            $major->slug = Str::slug($major->name);
            
            // Tự động set created_date và updated_date nếu không có
            if (!$major->created_date) {
                $major->created_date = now();
            }
            $major->updated_date = now();
            
            // Tự động tăng position nếu không có
            if (!$major->position || $major->position == 0) {
                $maxPosition = static::max('position') ?? 0;
                $major->position = $maxPosition + 1;
            }
        });
    }
}
