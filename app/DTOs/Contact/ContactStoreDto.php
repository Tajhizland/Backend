<?php

namespace App\DTOs\Contact;

class ContactStoreDto
{
    public function __construct(
        public string $name,
        public string $mobile,
        public mixed $message,
        public int $city_id,
        public int $province_id,
        public mixed $concept = null,
    )
    {
    }
}
