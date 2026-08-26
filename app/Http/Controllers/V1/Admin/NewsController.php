<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\News\NewsStoreDto;
use App\DTOs\News\NewsUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\News\StoreNewsRequest;
use App\Http\Requests\Admin\News\UpdateNewsRequest;
use App\Http\Resources\News\NewsResource;
use App\Services\New\NewServiceInterface;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function __construct(
        private readonly NewServiceInterface $newService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(NewsResource::collection($this->newService->dataTable()));
    }

    public function show($id)
    {
        return $this->dataResponse(new NewsResource($this->newService->find($id)));
    }

    public function store(StoreNewsRequest $request)
    {
        $this->newService->store(new NewsStoreDto(Auth::user()->id, ...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.news")]));
    }

    public function update($id, UpdateNewsRequest $request)
    {
        $this->newService->update(new NewsUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.news")]));
    }
}
