<?php

namespace App\Repositories\ResetPassword;

use App\Repositories\Base\BaseRepositoryInterface;

interface ResetPasswordRepositoryInterface extends  BaseRepositoryInterface
{
    public function setVerificationCode($username,$userId, $code);

    public function findPendingRequest($mobile);

    public function findInProgressRequest($mobile);

    public function setInProgress($id);

    public function setCompleted($id);
}
