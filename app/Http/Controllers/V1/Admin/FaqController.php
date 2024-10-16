<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Faq\StoreFaqRequest;
use App\Http\Requests\V1\Admin\Faq\UpdateFaqRequest;
use App\Http\Resources\V1\Faq\FaqCollection;
use App\Http\Resources\V1\Faq\FaqResource;
use App\Services\Faq\FaqServiceInterface;
use Illuminate\Support\Facades\Lang;

class FaqController extends Controller
{
    public function __construct
    (
        private FaqServiceInterface $faqService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new FaqCollection($this->faqService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new FaqResource($this->faqService->findById($id)));
    }

    public function store(StoreFaqRequest $request)
    {
        $this->faqService->store($request->get("question"), $request->get("answer"), $request->get("status"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.faq")]));
    }

    public function update(UpdateFaqRequest $request)
    {
        $this->faqService->update($request->get("id"), $request->get("question"), $request->get("answer"), $request->get("status"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.faq")]));
    }

}
