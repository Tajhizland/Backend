<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->integer('discount');
            $table->timestamp('discount_expire_time')->nullable();
            $table->foreignId('product_color_id');
            $table->timestamps();
            $table->index('product_color_id', 'color');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
