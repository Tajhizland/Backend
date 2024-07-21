<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->foreignId('filter_id');
            $table->foreignId('filter_item_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_filters');
    }
};
