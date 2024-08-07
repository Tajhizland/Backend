<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('on_hold_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->integer('status');
            $table->timestamp('expire_date')->nullable();
            $table->timestamp('review_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('on_hold_orders');
    }
};
