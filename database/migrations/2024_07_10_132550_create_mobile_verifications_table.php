<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mobile_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('mobile');
            $table->string('code');
            $table->integer('status');
            $table->timestamp('expire_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_verifications');
    }
};
