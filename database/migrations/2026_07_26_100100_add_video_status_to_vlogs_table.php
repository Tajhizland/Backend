<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vlogs', function (Blueprint $table) {
            if (!Schema::hasColumn('vlogs', 'video_status'))
                $table->string('video_status', 32)->nullable()->after('video');

            if (!Schema::hasColumn('vlogs', 'video_error'))
                $table->text('video_error')->nullable()->after('video_status');
        });

        // رکوردهای موجود که HLS دارند از قبل آماده‌اند؛ بقیه در صف فرض می‌شوند
        if (Schema::hasColumn('vlogs', 'hls')) {
            DB::table('vlogs')->whereNull('video_status')
                ->whereNotNull('hls')->where('hls', '<>', '')
                ->update(['video_status' => 'ready']);
        }

        DB::table('vlogs')->whereNull('video_status')->update(['video_status' => 'ready']);
    }

    public function down(): void
    {
        Schema::table('vlogs', function (Blueprint $table) {
            if (Schema::hasColumn('vlogs', 'video_status')) $table->dropColumn('video_status');
            if (Schema::hasColumn('vlogs', 'video_error')) $table->dropColumn('video_error');
        });
    }
};
