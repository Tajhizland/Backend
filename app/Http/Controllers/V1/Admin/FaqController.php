<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Faq\StoreFaqRequest;
use App\Http\Requests\Admin\Faq\UpdateFaqRequest;
use App\Http\Resources\Faq\FaqResource;
use App\Services\Faq\FaqServiceInterface;

class FaqController extends Controller
{
    public function __construct
    (
        private readonly FaqServiceInterface $faqService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(FaqResource::collection($this->faqService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new FaqResource($this->faqService->findById($id)));
    }

    public function store(StoreFaqRequest $request)
    {
        $this->faqService->store($request->get("question"), $request->get("answer"), $request->get("status"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.faq")]));
    }

    public function update(UpdateFaqRequest $request)
    {
        $this->faqService->update($request->get("id"), $request->get("question"), $request->get("answer"), $request->get("status"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.faq")]));
    }

}
