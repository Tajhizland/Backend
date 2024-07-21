<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('order_info_id');
            $table->integer('price');
            $table->integer('discount');
            $table->integer('final_price');
            $table->integer('status');
            $table->integer('payment_method');
            $table->timestamp('order_date');
            $table->string('tracking_number');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
