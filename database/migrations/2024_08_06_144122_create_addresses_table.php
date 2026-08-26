<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 256);
            $table->foreignId('city_id');
            $table->foreignId('province_id');
            $table->foreignId('user_id');
            $table->string('tell')->nullable();
            $table->string('mobile')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('address')->nullable();
            $table->integer('active')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
