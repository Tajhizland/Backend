<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * لاگ خام رویدادهای مارکتینگ (بازدید محصول، افزودن به سبد، مقایسه، علاقه‌مندی، خرید).
 *
 * همه گزارش‌های بخش مارکتینگ روی همین جدول جمع می‌شوند تا مسیر نوشتن یکی بماند و
 * افزودن رویداد جدید فقط یک مقدار در MarketingEventType باشد، نه یک جدول تازه.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('marketing_events', function (Blueprint $table) {
            $table->id();
            $table->string('type', 32);
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('session_id', 64)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['type', 'created_at'], 'marketing_events_type_created_idx');
            $table->index(['type', 'product_id', 'created_at'], 'marketing_events_type_product_created_idx');
            $table->index(['type', 'category_id', 'created_at'], 'marketing_events_type_category_created_idx');
            $table->index(['user_id', 'created_at'], 'marketing_events_user_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_events');
    }
};
