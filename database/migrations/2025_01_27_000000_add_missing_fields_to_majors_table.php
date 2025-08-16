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
            // Thêm trường IMAGE (thay thế cho icon hiện tại)
            $table->string('image', 550)->nullable()->comment('Hình ảnh đại diện')->after('description');
            
            // Thêm trường TYPE
            $table->string('type')->nullable()->comment('Loại ngành')->after('image');
            
            // Thêm trường Faculty_Institute (FK đến menus)
            $table->unsignedBigInteger('faculty_institute')->nullable()->comment('Thuộc Khoa/Viện/Phòng ban')->after('type');
            $table->foreign('faculty_institute')->references('id')->on('menus')->onDelete('set null');
            
            // Thêm trường CONTENTS (NTEXT/LONGTEXT)
            $table->longText('contents')->nullable()->comment('Nội dung chi tiết')->after('faculty_institute');
            
            // Thêm trường TOTAL_VIEW
            $table->integer('total_view')->default(0)->comment('Tổng số lượt xem')->after('contents');
            
            // Thêm trường LINK_URL
            $table->string('link_url')->nullable()->comment('Link URL')->after('total_view');
            
            // Thêm trường IS_HOME
            $table->tinyInteger('is_home')->default(0)->comment('Hiển thị trang chủ')->after('link_url');
            
            // Thêm trường POSITION
            $table->tinyInteger('position')->default(0)->comment('Thứ tự hiển thị')->after('is_home');
            
            // Thêm trường ISDELETE (soft delete)
            $table->tinyInteger('isdelete')->default(0)->comment('Xác định xóa')->after('position');
            
            // Thêm trường ISACTIVE (thay thế cho is_active hiện tại)
            $table->tinyInteger('isactive')->default(1)->comment('Trạng thái hiển thị')->after('isdelete');
            
            // Thêm trường CREATED_DATE và UPDATED_DATE (thay thế cho timestamps)
            $table->dateTime('created_date')->nullable()->comment('Ngày tạo')->after('isactive');
            $table->dateTime('updated_date')->nullable()->comment('Ngày sửa')->after('created_date');
            
            // Thêm trường CREATED_BY và UPDATED_BY (thay thế cho created_by, updated_by hiện tại)
            $table->unsignedBigInteger('created_by_id')->nullable()->comment('Mã người tạo')->after('updated_date');
            $table->unsignedBigInteger('updated_by_id')->nullable()->comment('Mã người sửa')->after('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('majors', function (Blueprint $table) {
            // Xóa foreign keys
            $table->dropForeign(['faculty_institute']);
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['updated_by_id']);
            
            // Xóa các trường đã thêm
            $table->dropColumn([
                'image',
                'type',
                'faculty_institute',
                'contents',
                'total_view',
                'link_url',
                'is_home',
                'position',
                'isdelete',
                'isactive',
                'created_date',
                'updated_date',
                'created_by_id',
                'updated_by_id'
            ]);
        });
    }
};
