<?php

namespace App\Repositories\MobileVerification;

use App\Enums\MobileVerificationStatus;
use App\Models\mobileVerification;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;

class MobileVerificationRepository extends BaseRepository implements MobileVerificationRepositoryInterface
{

    public function __construct(mobileVerification $model)
    {
        parent::__construct($model);
    }

    public function findByMobile($mobile)
    {
        // TODO: Implement findByMobile() method.
    }

    public function findPendingRequest($mobile)
    {
        return $this->model->where("mobile", $mobile)->pending()->unExpire()->first();
    }

    public function findInProgressRequest($mobile)
    {
        return $this->model->where("mobile", $mobile)->inProgress()->unExpire()->first();
    }

    public function setVerificationCode($mobile, $code)
    {
        return $this->create(["mobile" => $mobile, "status" => MobileVerificationStatus::Pending, "code" => $code, "expire_at" => Carbon::now()->addMinutes(config("settings.register.code_expire_minutes"))]);
    }

    public function setInProgress($id)
    {
        $this->update($this->findOrFail($id), ["status" => MobileVerificationStatus::InProgress]);
    }

    public function setCompleted($id)
    {
        $this->update($this->findOrFail($id), ["status" => MobileVerificationStatus::Completed]);
    }
}
