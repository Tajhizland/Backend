<?php

namespace App\DTOs\Address;

class AddressUpdateOrCreateDto
{
    public function __construct(
        public int    $user_id,
        public string $title,
        public int    $city_id,
        public int    $province_id,
        public string $mobile,
        public string $address,
        public mixed  $id = null,
        public mixed  $tell = null,
        public mixed  $zip_code = null,
    )
    {
    }
}
