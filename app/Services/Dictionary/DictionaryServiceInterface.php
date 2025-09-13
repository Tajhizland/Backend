<?php

namespace App\Services\Dictionary;

interface DictionaryServiceInterface
{
    public function dataTable();
    public function find($id);
    public function store($original_word, $mean);

    public function delete($id);

    public function update($id, $original_word, $mean);

    public function check($original_word);
}
