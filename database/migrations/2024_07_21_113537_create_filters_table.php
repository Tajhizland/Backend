<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('category_id');
            $table->integer('status');
            $table->timestamps();
            $table->index('category_id', 'category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filters');
    }
};
