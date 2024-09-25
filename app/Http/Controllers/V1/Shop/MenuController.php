<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Menu\MenuCollection;
use App\Services\Menu\MenuServiceInterface;

class MenuController extends Controller
{
    public function __construct
    (
        private  MenuServiceInterface $menuService
    )
    {
    }

    public function get()
    {
        return $this->dataResponseCollection(new MenuCollection($this->menuService->buildMenu()));
    }
}
