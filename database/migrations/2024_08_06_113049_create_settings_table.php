<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('phone');
            $table->string('fax');
            $table->string('about');
            $table->string('terms');
            $table->string('aparat');
            $table->string('instagram');
            $table->integer('homepage_most_popular_products');
            $table->integer('homepage_has_discount_products');
            $table->integer('homepage_new_products');
            $table->integer('homepage_custom_category_products');
            $table->integer('homepage_custom_category_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
