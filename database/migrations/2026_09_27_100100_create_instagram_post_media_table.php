<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('instagram_post_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instagram_post_id');
            /** image یا video — نوع از روی mime فایل آپلودی تعیین می‌شود */
            $table->string('type', 16);
            $table->string('file', 256);
            $table->integer('sort')->nullable();
            $table->timestamps();
            $table->index(['instagram_post_id', 'sort'], 'instagram_post_media_post_sort');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instagram_post_media');
    }
};
