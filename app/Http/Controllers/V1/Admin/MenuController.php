<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Menu\MenuStoreDto;
use App\DTOs\Menu\MenuUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Menu\StoreMenuRequest;
use App\Http\Requests\Admin\Menu\UpdateMenuRequest;
use App\Http\Resources\Menu\MenuResource;
use App\Services\Menu\MenuServiceInterface;

class MenuController extends Controller
{
    public function __construct(
        private readonly MenuServiceInterface $menuService,
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

    public function show($id)
    {
        return $this->dataResponse(new MenuResource($this->menuService->find($id)));
    }

    public function store(StoreMenuRequest $request)
    {
        $this->menuService->store(new MenuStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.menu")]));
    }

    public function update($id, UpdateMenuRequest $request)
    {
        $this->menuService->update(new MenuUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.menu")]));
    }

    public function destroy($id)
    {
        $this->menuService->delete($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.menu")]));
    }

    public function destroyBanner($id)
    {
        $this->menuService->deleteBanner($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.banner")]));
    }
}
