<?php

namespace App\Repositories\Slider;

use App\Models\News;
use App\Models\Slider;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class SliderRepository extends BaseRepository implements SliderRepositoryInterface
{
    public function __construct(Slider $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Slider::class)
            ->select("sliders.*")
            ->allowedFilters(['id', 'url', 'title', 'status', 'created_at', 'published'])
            ->allowedSorts(['id', 'url', 'title', 'status', 'created_at', 'published'])
            ->paginate($this->pageSize);
    }
    public function store($title ,$url ,$status, $image)
    {
        return $this->model::create(["title"=>$title , "url"=>$url , "image"=>$image , "status"=>$status]);
    }
    public function updateSlider(Slider $slider,$title ,$url ,$status, $image)
    {
        return $slider->update(["title"=>$title , "url"=>$url , "image"=>$image , "status"=>$status]);
    }
}
