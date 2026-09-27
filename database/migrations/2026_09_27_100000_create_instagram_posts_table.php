<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('instagram_posts', function (Blueprint $table) {
            $table->id();
            $table->text('caption')->nullable();
            $table->string('url', 256);
            $table->integer('status');
            $table->integer('view')->default(0);
            $table->integer('author')->nullable();
            $table->integer('sort')->nullable();
            $table->timestamps();
            $table->unique('url', 'instagram_posts_url_unique');
            $table->index(['status', 'sort'], 'instagram_posts_status_sort');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instagram_posts');
    }
};
