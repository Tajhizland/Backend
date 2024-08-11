<?php

namespace App\Services\Filter;

use App\Exceptions\BreakException;
use App\Repositories\Filter\FilterRepositoryInterface;
use App\Repositories\FilterItem\FilterItemRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Support\Facades\Lang;

class FilterService implements FilterServiceInterface
{
    public function __construct
    (
        private ProductRepositoryInterface    $productRepository,
        private FilterRepositoryInterface     $filterRepository,
        private FilterItemRepositoryInterface $filterItemRepository
    )
    {
    }

    public function apply($productQuery, $filters)
    {
        foreach ($filters as $filter => $value) {
            /** Dynamic Filters **/

            if (is_array($value)) {
                /** Example : filter[1][]=10  (filter[filter_id][]=filter_item_id) */
                $this->productRepository->otherFilter($filter, $value, $productQuery);
                continue;
            }

            /** Static Filters **/

            if ($filter == "minPrice") {
                /** Example : filter[minPrice]=10 */
                $this->productRepository->minPriceFilter($productQuery, $value);
                continue;
            }

            if ($filter == "maxPrice") {
                /** Example : filter[maxPrice]=10 */
                $this->productRepository->maxPriceFilter($productQuery, $value);
                continue;
            }

            if ($filter == "name" && $value != "") {
                /** Example : filter[name]=abc */
                $this->productRepository->nameFilter($productQuery, $value);
                continue;
            }

            if ($filter == "just_has_stock" && $value == 1) {
                /** Example : filter[just_has_stock]=1 */
                $this->productRepository->hasStockFilter($productQuery);
                continue;
            }


        }
        return $productQuery;
    }

    public function findById($id)
    {
        return $this->filterRepository->findOrFail($id);
    }

    public function dataTable()
    {
        return $this->filterRepository->dataTable();
    }

    public function createFilter($name, $categoryId, $status, $type, $items)
    {
        $filter = $this->filterRepository->createFilter($name, $categoryId, $status, $type);
        foreach ($items as $item) {
            $this->filterItemRepository->createFilterItem($filter->id, $item['value'], $item['status']);
        }
        return true;
    }

    public function updateFilter($id, $name, $categoryId, $status, $type, $items)
    {
        $this->filterRepository->updateFilter($id, $name, $categoryId, $status, $type);
        foreach ($items as $item) {
            if (isset($item["id"])) {
                $filterItem = $this->filterItemRepository->findOrFail($item["id"]);
                if ($filterItem->filter_id != $id)
                    throw new BreakException(Lang::get("exceptions.filter_item_filter_id_exception"));
                $this->filterItemRepository->updateFilterItem($filterItem, $item['value'], $item['status']);
            } else
                $this->filterItemRepository->createFilterItem($id, $item['value'], $item['status']);
        }
        return true;
    }
}