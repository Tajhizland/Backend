<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guaranties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('icon');
            $table->integer('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guaranties');
    }
};
