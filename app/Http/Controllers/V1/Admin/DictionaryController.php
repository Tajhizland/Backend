<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Dictionary\StoreDictionaryRequest;
use App\Http\Requests\V1\Admin\Dictionary\UpdateDictionaryRequest;
use App\Http\Resources\V1\Dictionary\DictionaryCollection;
use App\Http\Resources\V1\Dictionary\DictionaryResource;
use App\Services\Dictionary\DictionaryServiceInterface;
use Illuminate\Support\Facades\Lang;

class DictionaryController extends Controller
{
    public function __construct
    (
        private DictionaryServiceInterface $dictionaryServiceInterface
    )
    {
    }

    public function dataTable()
    {
        $response = $this->dictionaryServiceInterface->dataTable();
        return $this->dataResponseCollection(new DictionaryCollection($response));
    }

    public function find($id)
    {
        $response = $this->dictionaryServiceInterface->find($id);
        return $this->dataResponse(new DictionaryResource($response));
    }

    public function store(StoreDictionaryRequest $request)
    {
        $this->dictionaryServiceInterface->store($request->get("original_word"), $request->get("mean"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.dictionary")]));

    }

    public function update(UpdateDictionaryRequest $request)
    {
        $this->dictionaryServiceInterface->update($request->get("id"), $request->get("original_word"), $request->get("mean"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.dictionary")]));

    }

    public function remove($id)
    {
        $this->dictionaryServiceInterface->delete($id);
        return $this->successResponse(Lang::get("action.remove", ["attr" => Lang::get("attr.dictionary")]));

    }
}
