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
        Schema::create('web_links', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->comment('Tên liên kết');
            $table->string('url', 500)->comment('Đường dẫn liên kết');
            $table->string('description', 500)->nullable()->comment('Mô tả ngắn');
            $table->string('image', 500)->nullable()->comment('Ảnh đại diện');
            $table->dateTime('created_date')->nullable()->comment('Ngày tạo');
            $table->dateTime('updated_date')->nullable()->comment('Ngày sửa');
            $table->unsignedBigInteger('created_by')->nullable()->comment('Mã người tạo');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Mã người sửa');
            $table->boolean('is_active')->default(true)->comment('Kích hoạt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_links');
    }
};
