<?php

namespace App\Repositories\Slider;

use App\Models\Slider;
use App\Repositories\Base\BaseRepositoryInterface;

interface SliderRepositoryInterface extends  BaseRepositoryInterface
{
    public function dataTable();
    public function getActiveDesktopSlider();
    public function getActiveMobileSlider();
    public function store($title ,$url ,$status, $image);
    public function updateSlider(Slider $slider  ,$title ,$url ,$status, $image);
}
