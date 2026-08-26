<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lookup table imported from Tapin. Charset matches production (latin1).
     */
    public function up(): void
    {
        Schema::create('tapin_cities', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('province');
            $table->string('province_name', 80);
            $table->integer('city');
            $table->string('city_name', 80);
            $table->charset = 'latin1';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tapin_cities');
    }
};
