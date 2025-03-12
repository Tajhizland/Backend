<?php

namespace App\Repositories\Slider;

use App\Models\Slider;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class SliderRepository extends BaseRepository implements SliderRepositoryInterface
{
    public function __construct(Slider $model)
    {
        parent::__construct($model);
    }

    public function getActiveDesktopSlider()
    {
        return $this->model::active()->desktop()->get();
    }

    public function getActiveMobileSlider()
    {
        return $this->model::active()->mobile()->get();
    }

    public function dataTable()
    {
        return QueryBuilder::for(Slider::class)
            ->select("sliders.*")
            ->allowedFilters(['id', 'url', 'title', 'status', 'type', 'created_at', 'published'])
            ->allowedSorts(['id', 'url', 'title', 'status', 'type', 'created_at', 'published'])
            ->paginate($this->pageSize);
    }

    public function store($title, $url, $status, $image)
    {
        return $this->model::create(["title" => $title, "url" => $url, "image" => $image, "status" => $status]);
    }

    public function updateSlider(Slider $slider, $title, $url, $status, $image)
    {
        return $slider->update(["title" => $title, "url" => $url, "image" => $image, "status" => $status]);
    }

    public function getAllDesktop()
    {
        return $this->model::desktop()->get();

    }

    public function getAllMobile()
    {
        return $this->model::mobile()->get();
    }

    public function sort($id,$sort){
        return $this->model::where("id", $id)->update(["sort" => $sort]);

    }

}
