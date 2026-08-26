<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('status');
            $table->string('url');
            $table->string('image')->nullable();
            $table->integer('parent_id');
            $table->text('description')->nullable();
            $table->string('type', 256);
            $table->timestamps();
            $table->index('status', 'status');
            $table->index('parent_id', 'parent');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
