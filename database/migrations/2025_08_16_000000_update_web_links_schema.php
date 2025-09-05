<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('web_links', function (Blueprint $table) {
            if (!Schema::hasColumn('web_links', 'name')) {
                $table->string('name', 500)->comment('Tên thông báo; cty; đối tác')->after('id');
            }
            if (!Schema::hasColumn('web_links', 'link_url')) {
                $table->string('link_url', 550)->nullable()->comment('Link liên kết')->after('name');
            }
            if (!Schema::hasColumn('web_links', 'contents')) {
                $table->longText('contents')->nullable()->comment('Nội dung chi tiết')->after('link_url');
            }
            if (!Schema::hasColumn('web_links', 'code')) {
                $table->string('code')->nullable()->comment('mã trường, mã cty, (có thể null)')->after('id');
            }
            if (Schema::hasColumn('web_links', 'image')) {
                $table->string('image', 550)->nullable()->comment('Hình ảnh đại diện / Logo đối tác')->change();
            }
            if (!Schema::hasColumn('web_links', 'type')) {
                $table->string('type')->nullable()->comment('enum; Kiểu liên kết')->after('image');
            }
            if (!Schema::hasColumn('web_links', 'faculty_institute')) {
                $table->unsignedBigInteger('faculty_institute')->nullable()->comment('Thuộc: Khoa / Viện / Phòng ban nào?')->after('type');
            }
            if (!Schema::hasColumn('web_links', 'total_view')) {
                $table->integer('total_view')->default(0)->comment('TỔNG SỐ LƯỢT XEM')->after('contents');
            }
            if (!Schema::hasColumn('web_links', 'is_home')) {
                $table->tinyInteger('is_home')->default(0)->comment('Hiển thị trên trang chủ')->after('link_url');
            }
            if (!Schema::hasColumn('web_links', 'position')) {
                $table->tinyInteger('position')->default(0)->comment('Thứ tự')->after('is_home');
            }
            if (!Schema::hasColumn('web_links', 'isdelete')) {
                $table->tinyInteger('isdelete')->default(0)->comment('Xác định xóa')->after('position');
            }
            if (!Schema::hasColumn('web_links', 'isactive')) {
                $table->tinyInteger('isactive')->default(1)->comment('Trạng thái hiển thị')->after('isdelete');
            }
        });

        // Handle foreign keys separately to avoid issues during change
        if (Schema::hasTable('menus') && Schema::hasColumn('web_links', 'faculty_institute')) {
            Schema::table('web_links', function (Blueprint $table) {
                $table->foreign('faculty_institute')->references('id')->on('menus')->onDelete('set null');
            });
        }

        // Drop obsolete columns after creating replacements
        Schema::table('web_links', function (Blueprint $table) {
            if (Schema::hasColumn('web_links', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('web_links', 'url')) {
                $table->dropColumn('url');
            }
            if (Schema::hasColumn('web_links', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('web_links', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('web_links', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('web_links', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('web_links', function (Blueprint $table) {
            if (!Schema::hasColumn('web_links', 'title')) {
                $table->string('title', 255)->nullable();
            }
            if (!Schema::hasColumn('web_links', 'url')) {
                $table->string('url', 500)->nullable();
            }
            if (!Schema::hasColumn('web_links', 'description')) {
                $table->string('description', 500)->nullable();
            }
            if (!Schema::hasColumn('web_links', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('web_links', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
            if (!Schema::hasColumn('web_links', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }

            // Drop added columns
            foreach (['name','link_url','contents','code','type','faculty_institute','total_view','is_home','position','isdelete','isactive'] as $col) {
                if (Schema::hasColumn('web_links', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};


