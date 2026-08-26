<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('status');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
            $table->index('status', 'status');
            $table->index('start_date', 'start_date');
            $table->index('end_date', 'end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
