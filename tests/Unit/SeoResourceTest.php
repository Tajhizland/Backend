<?php

namespace Tests\Unit;

use App\Http\Resources\Sitemap\SitemapResource;
use App\Models\Product;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class SeoResourceTest extends TestCase
{
    public function test_sitemap_uses_latest_catalog_change_and_iso_date(): void
    {
        $product = new Product;
        $product->setRawAttributes([
            'url' => 'test-product', 'updated_at' => '2026-10-01 10:00:00',
            'prices_max_updated_at' => '2026-10-02 10:00:00',
            'stocks_max_updated_at' => '2026-10-03 10:00:00',
            'images_max_updated_at' => null,
        ]);
        $result = (new SitemapResource($product))->toArray(Request::create('/'));
        self::assertSame('test-product', $result['url']);
        self::assertSame('2026-10-03T10:00:00.000000Z', $result['updated_at']);
    }

    public function test_missing_modified_date_is_not_replaced_with_current_time(): void
    {
        $product = new Product;
        $product->setRawAttributes(['url' => 'test-product']);
        $result = (new SitemapResource($product))->toArray(Request::create('/'));
        self::assertNull($result['updated_at']);
    }
}
