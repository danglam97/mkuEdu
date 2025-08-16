<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Lecturer extends Model
{
    public $timestamps = false;
    
    // Constants cho các trường enum
    const TYPE_GIANG_VIEN = 'giang_vien';
    const TYPE_GIANG_VIEN_CHINH = 'giang_vien_chinh';
    const TYPE_GIANG_VIEN_CAO_CAP = 'giang_vien_cao_cap';
    const TYPE_TRO_GIANG = 'tro_giang';
    
    const ACADEMIC_DEGREE_CU_NHAN = 'cu_nhan';
    const ACADEMIC_DEGREE_KY_SU = 'ky_su';
    const ACADEMIC_DEGREE_THAC_SI = 'thac_si';
    const ACADEMIC_DEGREE_TIEN_SI = 'tien_si';
    const ACADEMIC_DEGREE_TIEN_SI_KHOA_HOC = 'tien_si_khoa_hoc';
    
    const ACADEMIC_TITLE_GIANG_VIEN = 'giang_vien';
    const ACADEMIC_TITLE_GIANG_VIEN_CHINH = 'giang_vien_chinh';
    const ACADEMIC_TITLE_GIANG_VIEN_CAO_CAP = 'giang_vien_cao_cap';
    const ACADEMIC_TITLE_PHO_GIANG_SU = 'pho_giao_su';
    const ACADEMIC_TITLE_GIAO_SU = 'giao_su';
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'position',
        'major_id',
        'description',
        'type',
        'faculty_institute',
        'academic_degree',
        'academic_title',
        'official_title',
        'is_research',
        'facebook',
        'zalo',
        'slug',
        'total_view',
        'link_url',
        'is_home',
        'position_order',
        'isdelete',
        'isactive',
        'created_date',
        'updated_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_research' => 'boolean',
        'is_home' => 'boolean',
        'isdelete' => 'boolean',
        'isactive' => 'boolean',
        'created_date' => 'datetime',
        'updated_date' => 'datetime',
        'total_view' => 'integer',
        'position_order' => 'integer',
    ];

    // Relationships
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function facultyInstitute(): BelongsTo
    {
        return $this->belongsTo(Menus::class, 'faculty_institute');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessors
    public function getFacultyInstituteFullNameAttribute()
    {
        if ($this->facultyInstitute) {
            return $this->facultyInstitute->name;
        }
        return 'N/A';
    }

    // Events
    protected static function booted()
    {
        static::saving(function ($lecturer) {
            $lecturer->slug = Str::slug($lecturer->name);
            
            // Tự động set created_date và updated_date nếu không có
            if (!$lecturer->created_date) {
                $lecturer->created_date = now();
            }
            $lecturer->updated_date = now();
            
            // Tự động tăng position_order nếu không có
            if (!$lecturer->position_order || $lecturer->position_order == 0) {
                $maxPosition = static::max('position_order') ?? 0;
                $lecturer->position_order = $maxPosition + 1;
            }
        });
    }
    
    // Methods để lấy options
    public static function getTypeOptions(): array
    {
        return [
            self::TYPE_GIANG_VIEN => 'Giảng viên',
            self::TYPE_GIANG_VIEN_CHINH => 'Giảng viên chính',
            self::TYPE_GIANG_VIEN_CAO_CAP => 'Giảng viên cao cấp',
            self::TYPE_TRO_GIANG => 'Trợ giảng',
        ];
    }
    
    public static function getAcademicDegreeOptions(): array
    {
        return [
            self::ACADEMIC_DEGREE_CU_NHAN => 'Cử nhân',
            self::ACADEMIC_DEGREE_KY_SU => 'Kỹ sư',
            self::ACADEMIC_DEGREE_THAC_SI => 'Thạc sĩ',
            self::ACADEMIC_DEGREE_TIEN_SI => 'Tiến sĩ',
            self::ACADEMIC_DEGREE_TIEN_SI_KHOA_HOC => 'Tiến sĩ khoa học',
        ];
    }
    
    public static function getAcademicTitleOptions(): array
    {
        return [
            self::ACADEMIC_TITLE_GIANG_VIEN => 'Giảng viên',
            self::ACADEMIC_TITLE_GIANG_VIEN_CHINH => 'Giảng viên chính',
            self::ACADEMIC_TITLE_GIANG_VIEN_CAO_CAP => 'Giảng viên cao cấp',
            self::ACADEMIC_TITLE_PHO_GIANG_SU => 'Phó Giáo sư',
            self::ACADEMIC_TITLE_GIAO_SU => 'Giáo sư',
        ];
    }
}
