<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Menu\StoreMenuRequest;
use App\Http\Requests\Admin\Menu\UpdateMenuRequest;
use App\Http\Resources\Menu\MenuResource;
use App\Services\Menu\MenuServiceInterface;

class MenuController extends Controller
{
    public function __construct
    (
        private readonly MenuServiceInterface $menuService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(MenuResource::collection($this->menuService->dataTable()));
    }

    public function list()
    {
        return $this->dataResponseCollection(MenuResource::collection($this->menuService->list()));
    }

    public function store(StoreMenuRequest $request)
    {
        $this->menuService->store($request->get("title"),$request->get("parent_id"),$request->get("url"),$request->get("status"),$request->get("category_id"),$request->get("banner_link"),$request->get("banner_logo"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.menu")]));
    }

    public function update(UpdateMenuRequest $request)
    {
        $this->menuService->update($request->get("id"),$request->get("title"),$request->get("parent_id"),$request->get("url"),$request->get("status"),$request->get("category_id"),$request->get("banner_link"),$request->get("banner_logo"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.menu")]));
    }

    public function delete($id)
    {
        $this->menuService->delete($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.menu")]));
    }
    public function deleteBanner($id)
    {
        $this->menuService->deleteBanner($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.banner")]));
    }

    public function findById($id)
    {
        return $this->dataResponse(new MenuResource($this->menuService->findById($id)));
    }
}
