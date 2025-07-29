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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500)->comment('Tên tin tức');
            $table->text('description')->nullable()->comment('Nội dung mô tả ngắn');
            $table->string('image', 550)->nullable()->comment('Hình ảnh đại diện');

            $table->unsignedBigInteger('id_category')->comment('ID danh mục tin (FK)');
            $table->text('contents')->nullable()->comment('Nội dung tin tức/bài viết');

            $table->integer('total_view')->default(0)->comment('Số lượt xem');
            $table->string('link_url', 550)->nullable()->comment('Link tập tin');
            $table->tinyInteger('is_home')->default(0)->comment('Hiển thị lên trang chủ (0: Không, 1: Có)');

            $table->string('slug', 160)->nullable()->comment('Sử dụng cho URL');
            $table->string('meta_title', 100)->nullable()->comment('Tiêu đề chuẩn SEO');
            $table->string('meta_keyword', 500)->nullable()->comment('Từ khóa theo chuẩn SEO');
            $table->string('meta_description', 500)->nullable()->comment('Mô tả theo chuẩn SEO');

            $table->dateTime('created_date')->nullable()->comment('Ngày tạo');
            $table->dateTime('updated_date')->nullable()->comment('Ngày sửa');

            $table->unsignedBigInteger('created_by')->nullable()->comment('Mã người tạo');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Mã người sửa');
            $table->unsignedBigInteger('approver_by')->nullable()->comment('Người duyệt bài');

            $table->tinyInteger('isdelete')->default(0)->comment('Xác định xóa (1: Xác định xóa)');
            $table->tinyInteger('isactive')->default(1)->comment('Trạng thái hiển thị (0: chờ duyệt, 1: đăng bài, 2: chờ duyệt (đã sửa) 3: từ chối');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
