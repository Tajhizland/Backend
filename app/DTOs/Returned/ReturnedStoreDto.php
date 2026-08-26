<?php

namespace App\DTOs\Returned;

class ReturnedStoreDto
{
    public function __construct(
        public int $userId,
        public int $order_id,
        public int $order_item_id,
        public int $count,
        public mixed $description = null,
        public mixed $file = null,
    )
    {
    }
}
