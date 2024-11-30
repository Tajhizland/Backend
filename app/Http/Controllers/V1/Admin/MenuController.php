<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Menu\StoreMenuRequest;
use App\Http\Requests\V1\Admin\Menu\UpdateMenuRequest;
use App\Http\Resources\V1\Menu\MenuCollection;
use App\Http\Resources\V1\Menu\MenuResource;
use App\Services\Menu\MenuServiceInterface;
use Illuminate\Support\Facades\Lang;

class MenuController extends Controller
{
    public function __construct
    (
        private MenuServiceInterface $menuService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new MenuCollection($this->menuService->dataTable()));
    }

    public function list()
    {
        return $this->dataResponseCollection(new MenuCollection($this->menuService->list()));
    }

    public function store(StoreMenuRequest $request)
    {
        $this->menuService->store($request->get("title"),$request->get("parent_id"),$request->get("url"),$request->get("status"),$request->get("banner_title"),$request->get("banner_link"),$request->get("banner_logo"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.menu")]));
    }

    public function update(UpdateMenuRequest $request)
    {
        $this->menuService->update($request->get("id"),$request->get("title"),$request->get("parent_id"),$request->get("url"),$request->get("status"),$request->get("banner_title"),$request->get("banner_link"),$request->get("banner_logo"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.menu")]));
    }

    public function findById($id)
    {
        return $this->dataResponse(new MenuResource($this->menuService->findById($id)));
    }
}
