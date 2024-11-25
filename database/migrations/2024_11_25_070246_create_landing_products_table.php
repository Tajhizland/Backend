<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('landing_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_id');
            $table->foreignId('product_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_products');
    }
};
