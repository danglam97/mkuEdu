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
        Schema::create('category_events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500)->comment('Tên danh mục loại');
            $table->text('notes')->nullable()->comment('Ghi chú');
            $table->string('icon', 250)->nullable()->comment('Hình ảnh cho danh mục');
            $table->unsignedBigInteger('id_parent')->nullable()->comment('ID danh mục cha');
            $table->tinyInteger('type')->default(0)->comment('Loại danh mục (0: Liên kết, 1: Nội dung)');
            $table->string('slug', 160)->unique()->comment('Sử dụng cho URL');
            $table->string('meta_title', 100)->nullable()->comment('Tiêu đề');
            $table->string('meta_keyword', 300)->nullable()->comment('Từ khóa theo chuẩn SEO');
            $table->string('meta_description', 300)->nullable()->comment('Mô tả');
            $table->dateTime('created_date')->nullable()->comment('Ngày tạo');
            $table->dateTime('updated_date')->nullable()->comment('Ngày sửa');
            $table->unsignedBigInteger('created_by')->nullable()->comment('Mã người tạo');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Mã người sửa');
            $table->tinyInteger('is_active')->default(1)->comment('Trạng thái hiển thị (1: Hiển thị, 0: Ẩn)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_events');
    }
};
