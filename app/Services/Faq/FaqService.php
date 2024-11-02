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
        return $this->faqRepository->findOrFail($id);
    }

    public function store($question, $answer, $status)
    {
        return $this->faqRepository->create([
            "question" => $question,
            "answer" => $answer,
            "status" => $status
        ]);
    }

    public function update($id, $question, $answer, $status)
    {
        $faq = $this->faqRepository->findOrFail($id);
        return $this->faqRepository->update($faq, [
            "question" => $question,
            "answer" => $answer,
            "status" => $status
        ]);
    }
}
