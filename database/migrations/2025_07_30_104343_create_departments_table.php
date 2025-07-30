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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên phòng ban');

            $table->string('code')->nullable()->comment('Mã phòng ban');

            $table->unsignedBigInteger('parent_id')->nullable()->comment('ID phòng ban cha');

            $table->text('description')->nullable()->comment('Mô tả phòng ban');

            $table->boolean('is_active')->default(true)->comment('Trạng thái hoạt động: 1-Hoạt động, 0-Không hoạt động');

            $table->foreign('parent_id')
                ->references('id')
                ->on('departments')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
