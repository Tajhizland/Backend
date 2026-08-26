<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('direct_uploads')) return;

        Schema::create('direct_uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('profile', 64);
            $table->string('key');
            $table->string('upload_id')->nullable();
            $table->string('original_name')->nullable();
            $table->string('mime', 128)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedBigInteger('confirmed_size')->nullable();
            $table->string('status', 32)->default('pending');
            $table->timestamps();
            $table->index('status');
            $table->index('user_id');
            $table->unique('key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('direct_uploads');
    }
};
