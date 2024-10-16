<?php

namespace App\Services\Faq;

interface FaqServiceInterface
{
    public function dataTable();
    public function getActive();
    public function findById($id);
    public function store($question, $answer, $status);
    public function update($id ,$question, $answer, $status);
}
