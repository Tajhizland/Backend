<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('url');
            $table->string('type', 256)->nullable();
            $table->integer('sort')->nullable();
            $table->timestamps();
            $table->index('type', 'type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
