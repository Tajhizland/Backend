<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('filter_items', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->string('status');
            $table->foreignId('filter_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filter_items');
    }
};
