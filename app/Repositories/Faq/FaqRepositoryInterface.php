<?php

namespace App\Repositories\Faq;

use App\Models\Faq;
use App\Repositories\Base\BaseRepositoryInterface;

interface FaqRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();
    public function getActive();
    public function store($question, $answer, $status);
    public function updateFaq(Faq $faq ,$question, $answer, $status);
}
