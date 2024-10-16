<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Faq\FaqCollection;
use App\Services\Faq\FaqServiceInterface;

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
        return $this->dataResponseCollection(new FaqCollection($this->faqService->getActive()));
    }
}
