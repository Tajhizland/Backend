<?php

namespace App\Repositories\OptionItem;

use App\Models\OptionItem;
use App\Repositories\Base\BaseRepository;

class OptionItemRepository extends BaseRepository implements OptionItemRepositoryInterface
{

    public function createFilterItem($optionId, $title, $status)
    {
        return $this->create([
            "option_id" => $optionId,
            "title" => $title,
            "status" => $status,
        ]);
    }

    public function updateFilterItem(OptionItem $optionItem, $title, $status)
    {
      return  $optionItem->update(
            [
                "title" => $title,
                "status" => $status,
            ]
        );
    }
}
