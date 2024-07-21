<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_infos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile');
            $table->string('tell');
            $table->integer('province_id');
            $table->integer('city_id');
            $table->text('address');
            $table->string('zip_code');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_infos');
    }
};
