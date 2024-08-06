<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id');
            $table->foreignId('province_id');
            $table->foreignId('user_id');
            $table->string('tell_code');
            $table->string('tell');
            $table->string('mobile');
            $table->string('zip_code');
            $table->text('address');
            $table->integer('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
