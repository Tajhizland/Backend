<?php

namespace App\Repositories\Menu;

use App\Models\Menu;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{
    public function __construct(Menu $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Menu::class)
            ->with("parent")
            ->allowedFilters(['id', 'created_at', 'title', 'url',
                AllowedFilter::callback('parent', function ($query, $value) {
                    $query->whereHas('parent', function ($query) use ($value) {
                        $query->where('title', 'like', '%' . $value . '%');
                    });
                }),])
            ->paginate($this->pageSize);
    }

    public function getWithChildren()
    {
        return $this->model::where("parent_id", 0)->with("children.children.children")->get();
    }

    public function store($title, $parentId, $url, $bannerTitle, $bannerUrl, $logoPath)
    {
        return $this->model::create([
            "title" => $title,
            "parent_id" => $parentId,
            "url" => $url,
            "banner_title" => $bannerTitle,
            "banner_link" => $bannerUrl,
            "banner_logo" => $logoPath
        ]);
    }

    public function updateMenu(Menu $menu, $title, $parentId, $url, $bannerTitle, $bannerUrl, $logoPath)
    {
        return $menu->update([
            "title" => $title,
            "parent_id" => $parentId,
            "url" => $url,
            "banner_title" => $bannerTitle,
            "banner_link" => $bannerUrl,
            "banner_logo" => $logoPath
        ]);
    }
}
