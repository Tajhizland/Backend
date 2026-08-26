<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Services\Faq\FaqServiceInterface;
use App\Http\Resources\Faq\FaqResource;

class FaqController extends Controller
{
    public function __construct
    (
        private FaqServiceInterface $faqService
    )
    {
    }
    public function getActive()
    {
        return $this->dataResponseCollection(FaqResource::collection($this->faqService->getActive()));
    }
}
