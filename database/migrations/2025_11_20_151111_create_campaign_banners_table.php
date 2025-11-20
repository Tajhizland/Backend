<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('campaign_banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id');
            $table->string('image');
            $table->string('url');
            $table->string('type');
            $table->integer('sort');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_banners');
    }
};
