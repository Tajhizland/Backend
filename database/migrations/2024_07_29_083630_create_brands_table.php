<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->integer('status');
            $table->string('image')->nullable();
            $table->string('banner', 256)->nullable();
            $table->text('description')->nullable();
            $table->integer('sort')->nullable();
            $table->timestamps();
            $table->index('status', 'status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
