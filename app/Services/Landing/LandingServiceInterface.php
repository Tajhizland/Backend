<?php

namespace App\Services\Landing;

use App\DTOs\Landing\LandingSetBannerDto;
use App\DTOs\Landing\LandingSetCategoryDto;
use App\DTOs\Landing\LandingSetProductDto;
use App\DTOs\Landing\LandingStoreDto;
use App\DTOs\Landing\LandingUpdateDto;

interface LandingServiceInterface
{
    public function store(LandingStoreDto $dto): mixed;
    public function update(LandingUpdateDto $dto): bool;
    public function find(int $id): mixed;
    public function dataTable();
    public function setProduct(LandingSetProductDto $dto): mixed;
    public function setCategory(LandingSetCategoryDto $dto): mixed;
    public function deleteProduct($id);
    public function deleteCategory($id);
    public function getProductByLanding($landingId);
    public function getCategoryByLanding($landingId);
    public function getBanner($landingId);
    public function deleteBanner($id);
    public function setBanner(LandingSetBannerDto $dto): mixed;
    public function getSitemapData();

    public function findByUrl($url);
}
