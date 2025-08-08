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
            $table->string('slug', 160)->nullable()->comment('Sử dụng cho URL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('majors', function (Blueprint $table) {
            //
        });
    }
};
