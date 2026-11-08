<?php

namespace App\Services\SnappPay;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductFeedService
{
    public function listing(int $page, int $size): LengthAwarePaginator
    {
        return $this->query()->orderBy('products.id')->paginate($size, ['*'], 'page', $page);
    }

    public function findById(int $id): Product
    {
        return $this->query()->findOrFail($id);
    }

    public function findBySlug(string $slug): Product
    {
        return $this->query()->where('url', $slug)->firstOrFail();
    }

    private function query(): Builder
    {
        // Keep out-of-stock products in the feed so the consumer can update availability.
        return Product::query()->active()->isProduct()->where('allow_snappay', 1)
            ->whereHas('activeProductColors.price', fn ($query) => $query->where('price', '>', 0))
            ->select(['id', 'name', 'url', 'description', 'brand_id', 'snappay_extra_price'])
            ->with([
                'brand:id,name',
                'images:id,product_id,url,sort',
                'categories:id,name,parent_id',
                'productOptions:id,product_id,option_item_id,value',
                'productOptions.optionItem:id,title',
                'activeProductColors:id,product_id,color_name,status,delivery_delay',
                'activeProductColors.price:id,product_color_id,price',
                'activeProductColors.stock:id,product_color_id,stock',
            ]);
    }
}
