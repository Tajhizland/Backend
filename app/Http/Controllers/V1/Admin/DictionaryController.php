<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dictionary\StoreDictionaryRequest;
use App\Http\Requests\Admin\Dictionary\UpdateDictionaryRequest;
use App\Http\Resources\Dictionary\DictionaryResource;
use App\Services\Dictionary\DictionaryServiceInterface;

class DictionaryController extends Controller
{
    public function __construct
    (
        private readonly DictionaryServiceInterface $dictionaryServiceInterface
    )
    {
    }

    public function dataTable()
    {
        $response = $this->dictionaryServiceInterface->dataTable();
        return $this->dataResponseCollection(DictionaryResource::collection($response));
    }

    public function find($id)
    {
        $response = $this->dictionaryServiceInterface->find($id);
        return $this->dataResponse(new DictionaryResource($response));
    }

    public function store(StoreDictionaryRequest $request)
    {
        $this->dictionaryServiceInterface->store($request->get("original_word"), $request->get("mean"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.dictionary")]));

    }

    public function update(UpdateDictionaryRequest $request)
    {
        $this->dictionaryServiceInterface->update($request->get("id"), $request->get("original_word"), $request->get("mean"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.dictionary")]));

    }

    public function remove($id)
    {
        $this->dictionaryServiceInterface->delete($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.dictionary")]));

    }
}
