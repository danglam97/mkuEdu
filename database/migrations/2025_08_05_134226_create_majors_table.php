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
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('tên ngành');
            $table->string('code')->unique()->nullable()->comment('mã ngành');
            $table->text('description')->nullable()->comment('mô tả');
            $table->tinyInteger('is_active')->default(0)->comment('trạng thái');
            $table->string('icon')->nullable()->comment('ảnh đại diện');
            $table->string('created_by')->nullable()->comment('người tạo');
            $table->string('updated_by')->nullable()->comment('người cập nhật');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('majors');
    }
};
