<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sms_log_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sms_log_id');
            $table->string('mobile');
            $table->text('message');
            $table->boolean('is_send');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_log_items');
    }
};
