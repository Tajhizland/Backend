<?php

namespace App\Services\Option;

use App\Repositories\Option\OptionRepositoryInterface;
use App\Repositories\OptionItem\OptionItemRepositoryInterface;

class OptionService implements OptionServiceInterface
{

    public function __construct
    (
        private OptionRepositoryInterface     $optionRepository,
        private OptionItemRepositoryInterface $optionItemRepository
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
}