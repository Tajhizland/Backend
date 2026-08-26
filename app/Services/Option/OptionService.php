<?php

namespace App\Services\Option;

use App\DTOs\Option\OptionStoreDto;
use App\DTOs\Option\OptionUpdateDto;
use App\Repositories\Option\OptionRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repositories\OptionItem\OptionItemRepositoryInterface;
use App\Repositories\ProductOption\ProductOptionRepositoryInterface;

readonly class OptionService implements OptionServiceInterface
{

    public function __construct
    (
        private OptionRepositoryInterface        $optionRepository,
        private OptionItemRepositoryInterface    $optionItemRepository,
        private ProductOptionRepositoryInterface $productOptionRepository,
    )
    {
    }

    public function find(int $id): mixed
    {
        $option = $this->optionRepository->find($id);
        if (!$option) {
            throw new NotFoundHttpException();
        }
        return $option;
    }

    public function getByProductIdAndCategoryId($productId, $categoryId)
    {
        return $this->productOptionRepository->getByProductIdAndCategoryId($productId, $categoryId);
    }

    public function dataTable()
    {
        return $this->optionRepository->dataTable();
    }

    public function store(OptionStoreDto $dto): bool
    {
        $lastSort = $this->optionRepository->findLastSortOfCategory($dto->category_id);
        $this->optionRepository->createOption($dto->title, $dto->category_id, $dto->status, $lastSort->sort + 1);

        return true;
    }

    public function update(OptionUpdateDto $dto): bool
    {
        $this->optionRepository->updateOption($dto->optionId, $dto->title, $dto->category_id, $dto->status);
        foreach ($dto->items as $item) {
            if (isset($item["id"]))
                $this->optionItemRepository->updateFilterItem($item["id"], $item["title"], $item["status"]);

        }
        return true;
    }

    public function getByProductId($productId)
    {
        return $this->optionItemRepository->getByProductId($productId);
    }

    public function setOptionToProduct($productId, $options): void
    {
        foreach ($options as $option) {
            $productOption = $this->productOptionRepository->findProductOption($productId, $option["item_id"]);
            if ($productOption) {
                if ($option["value"])

                    $this->productOptionRepository->updateValue($productOption, $option["value"]);
                else
                    $this->productOptionRepository->deleteValue($productOption);

                continue;
            }
            if ($option["value"])
                $this->productOptionRepository->store($productId, $option["item_id"], $option["value"]);
        }
    }

    public function getCategoryOptions($categoryId)
    {
        return $this->optionItemRepository->getCategoryOptions($categoryId);
    }

    public function setOption($categoryId, $options): void
    {
        foreach ($options as $option) {
            if (@$option["id"]) {
                $existOptionItem = $this->optionItemRepository->find($option["id"]);
                if ($existOptionItem) {
                    $this->optionItemRepository->update($existOptionItem, [
                        "title" => $option["title"],
                        "status" => $option["status"],
                    ]);
                    continue;
                }
            }
            $lastSort = $this->optionItemRepository->findLastSortOfCategory($categoryId);
            $sort = 1;
            if ($lastSort) {
                $sort = ($lastSort->sort ?? 0) + 1;
            }
            $this->optionItemRepository->create([
                "category_id" => $categoryId,
                "title" => $option["title"],
                "status" => $option["status"],
                "sort" => $sort,
            ]);
        }
    }

    public function sortOption($array)
    {
        foreach ($array as $item) {
            $this->optionItemRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function sortOptionItem($options)
    {
        foreach ($options as $item) {
            $this->optionItemRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function getItemOfOption($optionId)
    {
        return $this->optionItemRepository->getByOptionId($optionId);
    }

    public function updateOptionItem($id, $categoryId, $title, $status)
    {
        if ($id) {
            $option = $this->optionItemRepository->find($id);
            return $this->optionItemRepository->update($option,
                [
                    "title" => $title,
                    "status" => $status
                ]
            );
        }
        return $this->optionItemRepository->create(["title" => $title, "status" => $status, "category_id" => $categoryId]);

    }

    public function updateProductOption($id, $productId, $value, $optionItemId)
    {
        if ($id) {
            $option = $this->productOptionRepository->findOrFail($id);
            return $this->productOptionRepository->update($option,
                [
                    "value" => $value,
                ]
            );
        }
        return $this->productOptionRepository->create(["value" => $value, "product_id" => $productId, "option_item_id" => $optionItemId]);

    }
}
