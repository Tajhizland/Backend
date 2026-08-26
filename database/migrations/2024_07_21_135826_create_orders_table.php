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
            $table->integer('delivery_price');
            $table->integer('use_wallet_price')->default(0);
            $table->integer('final_price');
            $table->integer('total_price')->default(0)->comment('final_price + use_wallet_price');
            $table->integer('off')->default(0);
            $table->integer('status');
            $table->integer('payment_method');
            $table->integer('delivery_method');
            $table->timestamp('order_date');
            $table->timestamp('delivery_date')->nullable();
            $table->string('payment_token', 256)->nullable();
            $table->string('tracking_number');
            $table->string('delivery_token', 256)->nullable();
            $table->text('test')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
