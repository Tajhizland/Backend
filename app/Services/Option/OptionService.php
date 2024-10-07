<?php

namespace App\Services\Option;

use App\Repositories\Option\OptionRepositoryInterface;
use App\Repositories\OptionItem\OptionItemRepositoryInterface;
use App\Repositories\ProductOption\ProductOptionRepositoryInterface;

class OptionService implements OptionServiceInterface
{

    public function __construct
    (
        private OptionRepositoryInterface     $optionRepository,
        private OptionItemRepositoryInterface $optionItemRepository,
        private ProductOptionRepositoryInterface $productOptionRepository,
    )
    {
    }

    public function findById($id)
    {
        return $this->optionRepository->findOrFail($id);
    }

    public function dataTable()
    {
        return $this->optionRepository->dataTable();
    }

    public function createOption($title, $categoryId, $status, $items)
    {
        $option = $this->optionRepository->createOption($title, $categoryId, $status);
        foreach ($items as $item) {
            $this->optionItemRepository->createFilterItem($option->id, $item["title"], $item["status"]);
        }
        return true;
    }

    public function updateOption($id, $title, $categoryId, $status, $items)
    {
        $this->optionRepository->updateOption($id, $title, $categoryId, $status);
        foreach ($items as $item) {
            if (isset($item["id"]))
                $this->optionItemRepository->updateFilterItem($item["id"], $item["title"], $item["status"]);
            else
                $this->optionItemRepository->createFilterItem($id, $item["title"], $item["status"]);
        }
        return true;
    }
    public function getByProductId($productId)
    {
      return  $this->optionRepository->getByProductId($productId);
    }
    public function setOptionToProduct($productId, $options):void
    {
       foreach ($options as $option) {
            $productOption = $this->productOptionRepository->findProductOption($productId, $option["item_id"]);
            if ($productOption) {
                if($option["value"])

                $this->productOptionRepository->updateValue($productOption, $option["value"]);
                else
                $this->productOptionRepository->deleteValue($productOption);

                continue;
            }
            if($option["value"])
            $this->productOptionRepository->store($productId, $option["item_id"], $option["value"]);
        }
    }
    public function getCategoryOptions($categoryId)
    {
        return $this->optionRepository->getCategoryOptions($categoryId);
    }

    public function setOption($categoryId, $options): void
    {
        foreach ($options as $option) {
            $existOption = $this->optionRepository->find($option["id"]);
            if ($existOption) {
                $this->optionRepository->updateOption($option["id"], $option["title"], $categoryId, $option["status"]);
             }
            else {
                $this->optionRepository->createOption($option["title"], $categoryId, $option["status"]);
            }
            $optionItems = $option["optionItems"];
            foreach ($optionItems as $optionItem) {
                if(@$optionItem["id"]) {
                    $existOptionItem = $this->optionItemRepository->find($optionItem["id"]);
                    if ($existOptionItem) {
                        $this->optionItemRepository->updateFilterItem($existOptionItem, $optionItem["title"], $optionItem["status"]);
                        continue;
                    }
                }
                $this->optionItemRepository->createFilterItem($existOptionItem, $optionItem["title"], $optionItem["status"]);
            }
        }
    }
}
