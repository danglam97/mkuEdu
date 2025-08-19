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
        Schema::table('posts', function (Blueprint $table) {
            $table->dateTime('publish_date')->nullable()->comment('Ngày đăng bài (do người viết chọn)');
            $table->dateTime('approved_publish_date')->nullable()->comment('Ngày đăng bài được duyệt (do người duyệt sửa)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['publish_date', 'approved_publish_date']);
        });
    }
};
