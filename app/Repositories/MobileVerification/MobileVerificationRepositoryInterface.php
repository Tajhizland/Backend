<?php

namespace App\Repositories\MobileVerification;

use App\Repositories\Base\BaseRepositoryInterface;

interface MobileVerificationRepositoryInterface extends BaseRepositoryInterface
{
    public function setVerificationCode($mobile, $code);

    public function findPendingRequest($mobile);

    public function findInProgressRequest($mobile);

    public function setInProgress($id);

    public function setCompleted($id);
}
