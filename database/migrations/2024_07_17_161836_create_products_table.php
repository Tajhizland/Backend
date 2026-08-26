<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->integer('status');
            $table->integer('guaranty_id')->nullable();
            $table->integer('guaranty_time')->nullable();
            $table->integer('view');
            $table->integer('brand_id')->nullable();
            $table->text('description')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('study')->nullable();
            $table->text('review')->nullable();
            $table->string('type', 256)->default('product');
            $table->integer('weight')->nullable();
            $table->integer('box_id')->nullable();
            $table->integer('height')->nullable();
            $table->integer('width')->nullable();
            $table->integer('length')->nullable();
            $table->integer('use_packet')->default(0);
            $table->tinyInteger('is_stock')->nullable()->default(0);
            $table->integer('unboxing_video')->nullable();
            $table->integer('intro_video')->nullable();
            $table->integer('usage_video')->nullable();
            $table->text('unboxing_video_description')->nullable();
            $table->text('intro_video_description')->nullable();
            $table->text('usage_video_description')->nullable();
            $table->integer('stock_of')->nullable();
            $table->integer('testing_time')->nullable();
            $table->integer('allow_digipay')->default(0);
            $table->integer('allow_snappay')->default(0);
            $table->integer('digipay_extra_price')->default(0);
            $table->integer('sort')->nullable();
            $table->timestamps();
            $table->index('url', 'url');
            $table->index('brand_id', 'brand');
            $table->index('guaranty_id', 'guranty');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
