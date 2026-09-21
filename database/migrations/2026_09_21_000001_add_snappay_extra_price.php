<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('snappay_extra_price')->default(0);
        });
        Schema::table('order_items', function (Blueprint $table) {
            // Per-unit fee in toman, already included in final_price.
            $table->unsignedBigInteger('snappay_fee')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('order_items', fn (Blueprint $table) => $table->dropColumn('snappay_fee'));
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn('snappay_extra_price'));
    }
};
