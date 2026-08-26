<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_infos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('last_name', 256)->nullable();
            $table->string('national_code', 256)->nullable();
            $table->string('mobile');
            $table->string('tell')->nullable();
            $table->integer('province_id');
            $table->integer('city_id');
            $table->text('address');
            $table->string('zip_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_infos');
    }
};
