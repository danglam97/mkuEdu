<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lecturers', function (Blueprint $table) {
            // Thêm các trường mới theo schema
            $table->string('type')->nullable()->comment('Phân loại giảng viên: (Giảng viên; Giảng viên chính; ...)')->after('description');
            $table->unsignedBigInteger('faculty_institute')->nullable()->comment('Khoa / Viện / Phòng ban')->after('type');
            $table->foreign('faculty_institute')->references('id')->on('menus')->onDelete('set null');
            $table->string('academic_degree', 550)->nullable()->comment('Học vị (Cử nhân; Kỹ sư; Thạc sĩ; ...)')->after('faculty_institute');
            $table->string('academic_title', 550)->nullable()->comment('Học hàm (Phó giáo sư, Giáo sư; ...)')->after('academic_degree');
            $table->string('official_title', 550)->nullable()->comment('Chức danh quản lý (Trưởng khoa; Phó hiệu trưởng; Hiệu trưởng; ....)')->after('academic_title');
            $table->tinyInteger('is_research')->default(0)->comment('Tham gia nghiên cứu khoa học')->after('official_title');
            $table->string('facebook', 550)->nullable()->comment('Facebook')->after('is_research');
            $table->string('zalo', 550)->nullable()->comment('Zalo')->after('facebook');
            $table->string('slug')->nullable()->comment('rewrite url')->after('zalo');
            $table->integer('total_view')->default(0)->comment('TỔNG SỐ LƯỢT XEM')->after('slug');
            $table->string('link_url')->nullable()->comment('Link URL')->after('total_view');
            $table->tinyInteger('is_home')->default(0)->comment('Hiển thị trang chủ')->after('link_url');
            $table->tinyInteger('position_order')->default(0)->comment('Thứ tự sắp xếp')->after('is_home');
            $table->tinyInteger('isdelete')->default(0)->comment('Xác định xóa (1: Xác định xóa)')->after('position_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lecturers', function (Blueprint $table) {
            // Xóa foreign key
            $table->dropForeign(['faculty_institute']);
            
            // Xóa các trường đã thêm
            $table->dropColumn([
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
                'isdelete'
            ]);
        });
    }
};
