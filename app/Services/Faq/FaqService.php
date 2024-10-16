<?php

namespace App\Services\Faq;

use App\Repositories\Faq\FaqRepositoryInterface;

class FaqService implements FaqServiceInterface
{
    public function __construct(private FaqRepositoryInterface $faqRepository)
    {
    }

    public function dataTable()
    {
        return $this->faqRepository->dataTable();
    }

    public function getActive()
    {
        return $this->faqRepository->getActive();
    }

    public function findById($id)
    {
        return $this->findById($id);
    }

    public function store($question, $answer, $status)
    {
        return $this->store($question, $answer, $status);
    }

    public function update($id, $question, $answer, $status)
    {
        $faq = $this->faqRepository->findOrFail($id);
        return $this->faqRepository->updateFaq($faq, $question, $answer, $status);
    }
}
