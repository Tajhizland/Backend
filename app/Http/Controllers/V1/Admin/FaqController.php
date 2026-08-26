<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Faq\FaqStoreDto;
use App\DTOs\Faq\FaqUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Faq\StoreFaqRequest;
use App\Http\Requests\Admin\Faq\UpdateFaqRequest;
use App\Http\Resources\Faq\FaqResource;
use App\Services\Faq\FaqServiceInterface;

class FaqController extends Controller
{
    public function __construct(
        private readonly FaqServiceInterface $faqService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(FaqResource::collection($this->faqService->dataTable()));
    }

    public function show($id)
    {
        return $this->dataResponse(new FaqResource($this->faqService->find($id)));
    }

    public function store(StoreFaqRequest $request)
    {
        $this->faqService->store(new FaqStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.faq")]));
    }

    public function update($id, UpdateFaqRequest $request)
    {
        $this->faqService->update(new FaqUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.faq")]));
    }
}
