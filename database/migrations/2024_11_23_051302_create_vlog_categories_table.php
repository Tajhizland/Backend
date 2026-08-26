<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vlog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon', 256)->nullable();
            $table->string('url', 256);
            $table->string('status');
            $table->integer('sort')->nullable();
            $table->timestamps();
            $table->index('status', 'status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vlog_categories');
    }
};
