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
        Schema::table('majors', function (Blueprint $table) {
            // Xóa các trường cũ không còn sử dụng
            $table->dropColumn([
                'is_active',
                'icon',
                'created_by',
                'updated_by'
            ]);
            
            // Xóa timestamps mặc định của Laravel
            $table->dropTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('majors', function (Blueprint $table) {
            // Khôi phục các trường cũ
            $table->tinyInteger('is_active')->default(0)->comment('trạng thái');
            $table->string('icon')->nullable()->comment('ảnh đại diện');
            $table->string('created_by')->nullable()->comment('người tạo');
            $table->string('updated_by')->nullable()->comment('người cập nhật');
            
            // Khôi phục timestamps
            $table->timestamps();
        });
    }
};
