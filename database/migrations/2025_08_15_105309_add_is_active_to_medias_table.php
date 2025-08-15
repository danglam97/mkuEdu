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
        Schema::table('medias', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(0)->comment('Trạng thái hoạt động (0: không hoạt động, 1: Hoạt động)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medias', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
