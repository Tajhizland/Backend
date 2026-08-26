<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('popular_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->timestamps();
            $table->index('product_id', 'product');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('popular_products');
    }
};
