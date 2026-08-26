<?php

namespace App\Services\Dictionary;

use App\DTOs\Dictionary\DictionaryStoreDto;
use App\DTOs\Dictionary\DictionaryUpdateDto;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class DictionaryService implements DictionaryServiceInterface
{
    public function __construct(
        private DictionaryRepositoryInterface $dictionaryRepository,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->dictionaryRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $dictionary = $this->dictionaryRepository->find($id);
        if (!$dictionary) {
            throw new NotFoundHttpException();
        }
        return $dictionary;
    }

    public function store(DictionaryStoreDto $dto): mixed
    {
        return $this->dictionaryRepository->create([
            "original_word" => $dto->original_word,
            "mean" => $dto->mean,
        ]);
    }

    public function update(DictionaryUpdateDto $dto): bool
    {
        $dictionary = $this->find($dto->dictionaryId);
        return $this->dictionaryRepository->update($dictionary, [
            "original_word" => $dto->original_word,
            "mean" => $dto->mean,
        ]);
    }

    public function delete(int $id): bool|null
    {
        return $this->dictionaryRepository->delete($this->find($id));
    }

    public function check($original_word): mixed
    {
        return $this->dictionaryRepository->findByOriginalWord($original_word);
    }
}
