<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('option_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('option_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('title');
            $table->integer('status');
            $table->integer('sort')->nullable();
            $table->timestamps();
            $table->index('option_id', 'option');
            $table->index('category_id', 'category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('option_items');
    }
};
