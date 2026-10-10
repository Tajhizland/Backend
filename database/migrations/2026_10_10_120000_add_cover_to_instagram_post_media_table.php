<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('instagram_post_media', function (Blueprint $table) {
            $table->string('cover', 256)->nullable()->after('file');
        });
    }

    public function down(): void
    {
        Schema::table('instagram_post_media', function (Blueprint $table) {
            $table->dropColumn('cover');
        });
    }
};
