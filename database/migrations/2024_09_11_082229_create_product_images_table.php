<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->foreignId('product_id');
            $table->integer('sort')->nullable();
            $table->timestamps();
            $table->index('product_id', 'productId');
            $table->index('product_color_id', 'product_images_product_color_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
