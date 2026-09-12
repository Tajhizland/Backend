<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * تفکیک دستگاه بازدیدکننده روی لاگ بازدید صفحات.
 *
 * footprints هر تغییر مسیر در سایت را ثبت می‌کند، پس کامل‌ترین منبع برای پاسخ به
 * «کاربران با موبایل وارد می‌شوند یا دسکتاپ؟» همین جدول است. فقط نتیجه‌ی پارس‌شده
 * ذخیره می‌شود نه User-Agent خام، چون این جدول پرحجم است و رشته‌ی خام چند صد بایتی
 * به ازای هر بازدید صرفه ندارد.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('footprints', function (Blueprint $table) {
            $table->string('device', 16)->nullable()->after('ip');
            $table->string('platform', 32)->nullable()->after('device');
            $table->string('browser', 32)->nullable()->after('platform');

            $table->index(['device', 'created_at'], 'footprints_device_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('footprints', function (Blueprint $table) {
            $table->dropIndex('footprints_device_created_idx');
            $table->dropColumn(['device', 'platform', 'browser']);
        });
    }
};
