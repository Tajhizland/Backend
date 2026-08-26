<?php

namespace App\Services\Faq;

use App\DTOs\Faq\FaqStoreDto;
use App\DTOs\Faq\FaqUpdateDto;
use App\Repositories\Faq\FaqRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class FaqService implements FaqServiceInterface
{
    public function __construct(private FaqRepositoryInterface $faqRepository)
    {
    }

    public function dataTable(): mixed
    {
        return $this->faqRepository->dataTable();
    }

    public function getActive(): mixed
    {
        return $this->faqRepository->getActive();
    }

    public function find(int $id): mixed
    {
        $faq = $this->faqRepository->find($id);
        if (!$faq) {
            throw new NotFoundHttpException();
        }
        return $faq;
    }

    public function store(FaqStoreDto $dto): mixed
    {
        return $this->faqRepository->create([
            "question" => $dto->question,
            "answer" => $dto->answer,
            "status" => $dto->status,
        ]);
    }

    public function update(FaqUpdateDto $dto): bool
    {
        $faq = $this->find($dto->faqId);
        return $this->faqRepository->update($faq, [
            "question" => $dto->question,
            "answer" => $dto->answer,
            "status" => $dto->status,
        ]);
    }
}
