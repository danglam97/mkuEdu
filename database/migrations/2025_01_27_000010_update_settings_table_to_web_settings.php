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
        // Xóa bảng settings cũ nếu tồn tại
        Schema::dropIfExists('settings');
        
        // Tạo bảng WEB_SETTINGS mới theo schema
        Schema::create('web_settings', function (Blueprint $table) {
            $table->id(); // ID - BIGINT, Khóa chính
            
            // General Settings
            $table->string('name_uni', 500)->unique()->default('Trường Đại học Mở Hà Nội')->comment('Tên trường');
            $table->string('name_sologan', 500)->nullable()->comment('Sologan');
            $table->longText('description')->nullable()->comment('Nội dung mô tả ngắn');
            $table->string('logo', 550)->nullable()->comment('Hình ảnh đại diện / Logo');
            $table->string('favicon', 550)->nullable()->comment('Favicon - up lại thư mục gốc của web');
            $table->string('email', 550)->nullable()->comment('ĐỊA CHỈ EMAIL');
            $table->string('phone', 550)->nullable()->comment('ĐIỆN THOẠI (Nhập nhiều số điện thoại thì cách nhau bằng ; )');
            $table->longText('address')->nullable()->comment('Địa chỉ');
            
            // Link URL
            $table->string('link_url')->nullable()->comment('link trang');
            
            // Timestamps and Audit Fields
            $table->dateTime('created_at')->default(now())->comment('Ngày tạo');
            $table->dateTime('updated_at')->default(now())->comment('Ngày sửa');
            $table->unsignedBigInteger('created_by')->nullable()->comment('Mã người tạo');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Mã người sửa');
            $table->tinyInteger('isdelete')->default(0)->comment('Xác định xóa (1: Xác định xóa)');
            $table->tinyInteger('isactive')->default(1)->comment('Trạng thái hiển thị');
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_settings');
        
        // Tạo lại bảng settings cũ
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->text('footer_text')->nullable();
            $table->timestamps();
        });
    }
};
