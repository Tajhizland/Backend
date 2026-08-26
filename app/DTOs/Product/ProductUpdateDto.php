<?php

namespace App\DTOs\Product;

class ProductUpdateDto
{
    public function __construct(
        public int    $productId,
        public string $name,
        public string $url,
        public mixed  $type,
        public int    $status,
        public mixed  $categoryId,
        public mixed  $description = null,
        public mixed  $study = null,
        public mixed  $meta_title = null,
        public mixed  $meta_description = null,
        public mixed  $brand_id = null,
        public mixed  $guaranty_id = null,
        public mixed  $guaranty_time = null,
        public mixed  $review = null,
        public mixed  $is_stock = 0,
        public mixed  $testing_time = null,
        public mixed  $stock_of = null,
        public mixed  $length = null,
        public mixed  $width = null,
        public mixed  $height = null,
        public mixed  $weight = null,
        public mixed  $use_packet = null,
    )
    {
    }
}
