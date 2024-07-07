<?php

namespace App\Repositories\User;

use App\Interface\UserInterface;
use App\Models\User;
use App\Repositories\Base\BaseRepository;

class UserRepository extends BaseRepository implements  UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
