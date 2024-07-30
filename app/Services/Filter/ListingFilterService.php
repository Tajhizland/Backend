<?php

namespace App\Services\Filter;

use App\Repositories\Product\ProductRepositoryInterface;

class ListingFilterService implements ListingFilterServiceInterface
{
    public function __construct
    (
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    public function apply($productQuery, $filters)
    {
        foreach ($filters as $filter => $value) {
            /** Dynamic Filters **/

            if (is_array($value)) { /** Example : filter[1][]=10  (filter[filter_id][]=filter_item_id) */
                $this->productRepository->otherFilter($filter, $value, $productQuery);
                continue;
            }

            /** Static Filters **/

            if ($filter == "minPrice") { /** Example : filter[minPrice]=10 */
                $this->productRepository->minPriceFilter($productQuery, $value);
                continue;
            }

            if ($filter == "maxPrice") { /** Example : filter[maxPrice]=10 */
                $this->productRepository->maxPriceFilter($productQuery, $value);
                continue;
            }

            if ($filter == "name" && $value!="") { /** Example : filter[name]=abc */
                $this->productRepository->nameFilter($productQuery, $value);
                continue;
            }

            if ($filter == "just_has_stock" && $value==1) {/** Example : filter[just_has_stock]=1 */
                $this->productRepository->hasStockFilter($productQuery);
                continue;
            }


        }
        return $productQuery;
    }
}
