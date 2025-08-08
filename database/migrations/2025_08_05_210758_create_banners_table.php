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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();        // Tiêu đề banner
            $table->string('image')->nullable();        // Đường dẫn ảnh
            $table->string('link')->nullable();         // URL khi click
            $table->string('position')->nullable();     // Vị trí hiển thị (ví dụ: home_top, sidebar, etc.)
            $table->integer('order')->default(0);       // Thứ tự sắp xếp
            $table->longText('description')->nullable(); // Mô tả
            $table->foreignId('created_by')->nullable(); // Người tạo
            $table->foreignId('updated_by')->nullable(); // Người sửa
            $table->timestamp('created_at')->nullable(); // Ngày tạo
            $table->timestamp('updated_at')->nullable(); // Ngày sửa
            $table->boolean('is_active')->default(0); // Trạng thái hiển thị
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
