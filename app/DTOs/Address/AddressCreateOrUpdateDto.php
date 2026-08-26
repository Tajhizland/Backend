<?php

namespace App\DTOs\Address;

class AddressCreateOrUpdateDto
{
    public function __construct(
        public int $userId,
        public int $city_id,
        public int $province_id,
        public string $mobile,
        public string $address,
        public mixed $tell = null,
        public mixed $zip_code = null,
    )
    {
    }
}
