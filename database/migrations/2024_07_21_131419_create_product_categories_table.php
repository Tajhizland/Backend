<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->foreignId('category_id');
            $table->timestamps();
            $table->index(['product_id', 'category_id'], 'productCategory');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
