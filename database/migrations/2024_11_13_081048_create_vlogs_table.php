<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vlogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('category_id');
            $table->text('description')->nullable();
            $table->string('url');
            $table->string('video');
            $table->string('hls', 256)->nullable();
            $table->integer('status');
            $table->integer('view')->default(0);
            $table->string('poster', 256);
            $table->integer('author')->nullable();
            $table->integer('sort')->nullable();
            $table->timestamps();
            $table->index(['url', 'status'], 'url');
            $table->index('category_id', 'category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vlogs');
    }
};
