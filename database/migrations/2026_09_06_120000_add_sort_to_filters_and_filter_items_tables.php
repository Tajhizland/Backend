<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * ستون sort برای فیلترهای دسته‌بندی و آیتم‌های آن‌ها، هم‌سان با ویژگی‌ها (options)
 * تا بتوان ترتیب نمایش فیلترها را در سایدبار فروشگاه از پنل مدیریت تعیین کرد.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('filters', function (Blueprint $table) {
            $table->integer('sort')->nullable()->after('status');
        });

        Schema::table('filter_items', function (Blueprint $table) {
            $table->integer('sort')->nullable()->after('status');
        });

        /** ترتیب اولیه = ترتیب فعلی (بر اساس id) تا چیزی جابه‌جا نشود. */
        DB::statement('UPDATE filters SET sort = id WHERE sort IS NULL');
        DB::statement('UPDATE filter_items SET sort = id WHERE sort IS NULL');
    }

    public function down(): void
    {
        Schema::table('filters', function (Blueprint $table) {
            $table->dropColumn('sort');
        });

        Schema::table('filter_items', function (Blueprint $table) {
            $table->dropColumn('sort');
        });
    }
};
