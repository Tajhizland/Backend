<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('returneds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('order_id');
            $table->foreignId('order_item_id');
            $table->integer('count');
            $table->text('description');
            $table->integer('status');
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returneds');
    }
};
