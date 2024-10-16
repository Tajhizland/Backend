<?php

namespace App\Repositories\Page;

use App\Models\Delivery;
use App\Models\Page;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class PageRepository extends BaseRepository implements PageRepositoryInterface
{
    public function __construct(Page $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Page::class)
            ->allowedFilters(['title', 'url', 'status', 'id', 'created_at'])
            ->allowedSorts(['title', 'url', 'status', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function findByUrl($url)
    {
        return $this->model::where("url",$url)->active()->first();
    }

    public function store($title, $url, $image, $content, $status)
    {
      return  $this->model::create([
            "title"=>$title,
            "url"=>$url,
            "image"=>$image,
            "status"=>$status,
        ]);
    }

    public function updateFaq(Page $page ,$title , $url , $image , $content , $status)
    {
        return $page->update([
            "title" => $title,
            "status" => $status,
            "url" => $url,
            "image" => $image,
            "content" => $content
        ]);
    }
}
