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
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->comment('Tên album');
            $table->string('page')->nullable()->comment('Trang hiển thị, ví dụ: home, about');
            $table->string('position')->nullable()->comment('Vị trí hiển thị');
            $table->tinyInteger('isactive')->comment('trạng thái hiển thị')->nullable()->default(0);
            $table->timestamps();
        });

        Schema::create('album_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('album_id');
            $table->string('image')->comment('Đường dẫn ảnh');
            $table->enum('type', ['main', 'sub'])->comment('main: ảnh chính, sub: ảnh phụ');
            $table->integer('order')->default(0)->comment('Thứ tự ảnh phụ');
            $table->timestamps();

            $table->foreign('album_id')->references('id')->on('albums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('album_items');
        Schema::dropIfExists('albums');
    }
};
