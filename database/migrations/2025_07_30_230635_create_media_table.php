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
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->string('title')
                ->nullable()
                ->comment('Tiêu đề hoặc tên hiển thị của media');

            $table->enum('type', ['image', 'video'])
                ->comment('Loại media: image (ảnh), video');

            $table->enum('source', ['file', 'link'])
                ->nullable()
                ->comment('Nguồn video: file = tải lên, link = YouTube/Vimeo');

            $table->string('url')
                ->comment('Đường dẫn file hoặc link video');

            $table->string('thumbnail')
                ->nullable()
                ->comment('Ảnh đại diện (thumbnail) cho video');

            $table->string('page')
                ->nullable()
                ->comment('Trang sử dụng media, ví dụ: home, about');

            $table->string('position')
                ->nullable()
                ->comment('Vị trí hiển thị, ví dụ: top_banner, footer');

            $table->text('description')
                ->nullable()
                ->comment('Mô tả nội dung media');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};
