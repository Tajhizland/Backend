<?php

namespace App\Services\Filter;


interface ListingFilterServiceInterface
{
    public function apply($productQuery , $filters);
}
