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
        Schema::create('contact_infor', function (Blueprint $table) {
            $table->id(); // ID - BIGINT, Khóa chính
            
            // Thông tin cơ bản
            $table->string('name', 500)->comment('Tên người cần liên hệ');
            $table->longText('addresss')->nullable()->comment('ĐỊA CHỈ LIÊN HỆ');
            $table->string('image', 550)->nullable()->comment('Hình ảnh đại diện');
            $table->string('type', 550)->nullable()->comment('Loại liên hệ');
            
            // Foreign keys
            $table->unsignedBigInteger('major_id')->nullable()->comment('Thuộc chuyên ngành nào (FK)');
            $table->unsignedBigInteger('faculty_institute')->nullable()->comment('Khoa / Viện / Phòng ban');
            
            // Thông tin liên hệ
            $table->string('email', 500)->nullable()->unique()->comment('Email liên hệ');
            $table->string('phone', 500)->nullable()->comment('Số điện thoại');
            $table->string('facebook')->nullable()->comment('Link Facebook');
            $table->string('zalo')->nullable()->comment('Link Zalo');
            
            // SEO và hiển thị
            $table->string('slug')->nullable()->comment('Rewrite URL');
            $table->integer('total_view')->default(0)->comment('TỔNG SỐ LƯỢT XEM');
            $table->string('link_url')->nullable()->comment('Địa chỉ website nếu có (link khoa / viện / ...)');
            $table->tinyInteger('is_home')->default(0)->comment('Hiển thị trang chủ');
            $table->tinyInteger('position')->default(0)->comment('Thứ tự sắp xếp');
            
            // Timestamps and Audit Fields
            $table->dateTime('created_date')->default(now())->comment('Ngày tạo');
            $table->dateTime('updated_date')->default(now())->comment('Ngày sửa');
            $table->unsignedBigInteger('created_by')->nullable()->comment('Mã người tạo');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Mã người sửa');
            $table->tinyInteger('isdelete')->default(0)->comment('Xác định xóa (1: Xác định xóa)');
            $table->tinyInteger('isactive')->default(1)->comment('Trạng thái hiển thị');
            
            // Foreign keys
            $table->foreign('major_id')->references('id')->on('majors')->onDelete('set null');
            $table->foreign('faculty_institute')->references('id')->on('menus')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_infor');
    }
};
