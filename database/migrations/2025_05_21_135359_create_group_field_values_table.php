<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('group_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_product_id');
            $table->foreignId('group_field_id');
            $table->string('value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_field_values');
    }
};
