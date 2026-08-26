<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Dictionary\DictionaryStoreDto;
use App\DTOs\Dictionary\DictionaryUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dictionary\StoreDictionaryRequest;
use App\Http\Requests\Admin\Dictionary\UpdateDictionaryRequest;
use App\Http\Resources\Dictionary\DictionaryResource;
use App\Services\Dictionary\DictionaryServiceInterface;

class DictionaryController extends Controller
{
    public function __construct(
        private readonly DictionaryServiceInterface $dictionaryService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(DictionaryResource::collection($this->dictionaryService->dataTable()));
    }

    public function show($id)
    {
        return $this->dataResponse(new DictionaryResource($this->dictionaryService->find($id)));
    }

    public function store(StoreDictionaryRequest $request)
    {
        $this->dictionaryService->store(new DictionaryStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.dictionary")]));
    }

    public function update($id, UpdateDictionaryRequest $request)
    {
        $this->dictionaryService->update(new DictionaryUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.dictionary")]));
    }

    public function destroy($id)
    {
        $this->dictionaryService->delete($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.dictionary")]));
    }
}
