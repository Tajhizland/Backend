<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * دستگاه روی رویدادهای مارکتینگ، تا بتوان نرخ تبدیل را به تفکیک دستگاه دید:
 * معمولا سهم بازدید موبایل بالاست ولی سهم خرید پایین‌تر، و همین شکاف نقطه‌ی بهبود است.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('marketing_events', function (Blueprint $table) {
            $table->string('device', 16)->nullable()->after('session_id');

            $table->index(['device', 'type', 'created_at'], 'marketing_events_device_type_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('marketing_events', function (Blueprint $table) {
            $table->dropIndex('marketing_events_device_type_created_idx');
            $table->dropColumn('device');
        });
    }
};
