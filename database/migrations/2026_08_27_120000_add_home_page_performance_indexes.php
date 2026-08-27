<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ایندکس‌هایی که کوئری‌های صفحه اصلی (و در کل لیست محصولات) به آن‌ها نیاز دارند.
 *
 * - comments.(product_id,status): محاسبه امتیاز و تعداد نظرِ هر محصول بدون این ایندکس
 *   کل جدول comments را اسکن می‌کرد.
 * - products.status: اسکوپ active() تقریبا در همه کوئری‌های فروشگاه استفاده می‌شود.
 * - product_categories.(category_id,product_id): ایندکس فعلی با پیشوند product_id
 *   برای جهت «دسته‌بندی -> محصولات» قابل استفاده نبود.
 * - stocks.(product_color_id,stock) و discount_items.(product_color_id,top):
 *   شرط‌های whereHas پرتکرار در اسکوپ‌های hasDiscount/hasColorHasStock.
 */
return new class extends Migration {
    /**
     * @var array<string, array{columns: string[], name: string}>
     */
    private array $indexes = [
        'comments' => ['columns' => ['product_id', 'status'], 'name' => 'comments_product_status_index'],
        'products' => ['columns' => ['status'], 'name' => 'products_status_index'],
        'product_categories' => ['columns' => ['category_id', 'product_id'], 'name' => 'product_categories_category_product_index'],
        'stocks' => ['columns' => ['product_color_id', 'stock'], 'name' => 'stocks_color_stock_index'],
        'discount_items' => ['columns' => ['product_color_id', 'top'], 'name' => 'discount_items_color_top_index'],
        'special_products' => ['columns' => ['homepage', 'sort'], 'name' => 'special_products_homepage_sort_index'],
    ];

    public function up(): void
    {
        foreach ($this->indexes as $table => $index) {
            if (!Schema::hasTable($table) || $this->hasIndex($table, $index['name'])) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($index) {
                $blueprint->index($index['columns'], $index['name']);
            });
        }
    }

    public function down(): void
    {
        foreach ($this->indexes as $table => $index) {
            if (!Schema::hasTable($table) || !$this->hasIndex($table, $index['name'])) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($index) {
                $blueprint->dropIndex($index['name']);
            });
        }
    }

    private function hasIndex(string $table, string $name): bool
    {
        return collect(Schema::getIndexes($table))->contains(
            fn (array $index) => strtolower($index['name']) === strtolower($name)
        );
    }
};
