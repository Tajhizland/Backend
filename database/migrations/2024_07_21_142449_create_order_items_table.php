<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->foreignId('product_color_id');
            $table->integer('count');
            $table->integer('price');
            $table->integer('dicount');
            $table->integer('final_price');
            $table->integer('unit_price');
            $table->integer('unit_discount');
            $table->foreignId('order_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
