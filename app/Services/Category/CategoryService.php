<?php

namespace App\Services\Category;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\Filter\ListingFilterService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private ProductRepositoryInterface  $productRepository,
        private ListingFilterService        $listingFilterService
    )
    {
    }

    public function listing($url, $filters)
    {
        $category = $this->categoryRepository->findByUrl($url);
        if (!$category) {
            throw new NotFoundHttpException();
        }
        $productsQuery = $this->productRepository->activeProductQuery();
        $productsQuery = $this->listingFilterService->apply($productsQuery, $filters);
        $products = $this->productRepository->paginated($productsQuery);

        return ["products" => $products, "category" => $category];
    }
}
