<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * لاگ جستجوهای فروشگاه به همراه تعداد نتیجه.
 *
 * result_count کل نتایج (محصول + بلاگ + دسته) است و product_count فقط محصول‌ها؛ چون
 * جستجویی که یک مقاله برمی‌گرداند ولی هیچ محصولی ندارد، از نگاه فروش همان «بی‌نتیجه» است.
 * term عبارت خام کاربر است و normalized_term نسخه یکسان‌سازی‌شده (برای گروه‌بندی درست فارسی).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('search_logs', function (Blueprint $table) {
            $table->id();
            $table->string('term', 255);
            $table->string('normalized_term', 191);
            $table->unsignedInteger('result_count')->default(0);
            $table->unsignedInteger('product_count')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('session_id', 64)->nullable();
            $table->string('source', 32)->default('shop');
            $table->timestamps();

            $table->index(['normalized_term', 'created_at'], 'search_logs_term_created_idx');
            $table->index(['result_count', 'created_at'], 'search_logs_result_created_idx');
            $table->index(['product_count', 'created_at'], 'search_logs_product_count_created_idx');
            $table->index('created_at', 'search_logs_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_logs');
    }
};
