<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();
            $table->string('color_name');
            $table->string('color_code');
            $table->string('status');
            $table->foreignId('product_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_colors');
    }
};
