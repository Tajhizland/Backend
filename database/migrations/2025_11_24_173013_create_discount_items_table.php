<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discount_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id');
            $table->foreignId('product_color_id');
            $table->integer('discount_price')->nullable();
            $table->timestamp('discount_expire_time')->nullable();
            $table->integer('top')->default(0);
            $table->integer('sort')->nullable();
            $table->timestamps();
            $table->index('discount_id', 'discountId');
            $table->index('product_color_id', 'color');
            $table->index('top', 'top');
            $table->index('sort', 'sort');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_items');
    }
};
