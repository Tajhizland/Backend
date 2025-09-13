<?php

namespace App\Services\Dictionary;

use App\Repositories\Dictionary\DictionaryRepositoryInterface;

class DictionaryService implements DictionaryServiceInterface
{
    public function __construct
    (
        private DictionaryRepositoryInterface $dictionaryRepository
    )
    {
    }

    public function store($original_word, $mean)
    {
        return $this->dictionaryRepository->create([
            "original_word" => $original_word,
            "mean" => $mean
        ]);
    }

    public function delete($id)
    {
        $model = $this->dictionaryRepository->findOrFail($id);
        return $this->dictionaryRepository->delete($model);
    }

    public function update($id, $original_word, $mean)
    {
        $model = $this->dictionaryRepository->findOrFail($id);
        $this->dictionaryRepository->update($model, [
            "original_word" => $original_word,
            "mean" => $mean
        ]);
    }

    public function check($original_word)
    {
        return $this->dictionaryRepository->findByOriginalWord($original_word);
    }

    public function dataTable()
    {
        return $this->dictionaryRepository->dataTable();
    }

    public function find($id)
    {
        return $this->dictionaryRepository->findOrFail($id);
    }
}
