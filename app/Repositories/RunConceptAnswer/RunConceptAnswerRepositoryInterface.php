<?php

namespace App\Repositories\RunConceptAnswer;

use App\Repositories\Base\BaseRepositoryInterface;

interface RunConceptAnswerRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();

    public function getByQuestionId($id);
}
