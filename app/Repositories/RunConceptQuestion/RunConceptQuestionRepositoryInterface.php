<?php

namespace App\Repositories\RunConceptQuestion;

use App\Repositories\Base\BaseRepositoryInterface;

interface RunConceptQuestionRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();
    public function list();
}
