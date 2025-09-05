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
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('Tên giảng viên');
            $table->string('email', 255)->unique()->comment('Email');
            $table->string('phone', 20)->nullable()->comment('Số điện thoại');
            $table->string('avatar')->nullable()->comment('Ảnh đại diện');
            $table->string('position', 255)->nullable()->comment('Chức vụ');
            $table->string('major_id', 255)->nullable()->comment('Chuyên ngành đào tạo');
            $table->text('description')->nullable()->comment('Giới thiệu ngắn');
            $table->tinyInteger('isactive')->default(0)->comment('Trạng thái hoạt động (0: không hoạt động, 1: Hoạt động)');
            $table->dateTime('created_date')->nullable()->comment('Ngày tạo');
            $table->dateTime('updated_date')->nullable()->comment('Ngày sửa');
            $table->unsignedBigInteger('created_by')->nullable()->comment('Mã người tạo');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Mã người sửa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};


