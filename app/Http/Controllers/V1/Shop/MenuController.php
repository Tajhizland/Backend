<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Services\Menu\MenuServiceInterface;
use App\Http\Resources\Menu\MenuResource;

class MenuController extends Controller
{
    public function __construct
    (
        private readonly MenuServiceInterface $menuService
    )
    {
    }

    public function get()
    {
        return $this->dataResponseCollection(MenuResource::collection($this->menuService->buildMenu()));
    }
}
