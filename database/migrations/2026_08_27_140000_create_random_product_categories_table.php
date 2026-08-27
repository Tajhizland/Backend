<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('random_product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->timestamps();
            $table->unique('category_id', 'category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('random_product_categories');
    }
};
