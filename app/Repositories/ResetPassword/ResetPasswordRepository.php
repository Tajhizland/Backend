<?php

namespace App\Repositories\ResetPassword;

use App\Enums\ResetPasswordStatus;
use App\Models\ResetPassword;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;

class ResetPasswordRepository extends BaseRepository implements ResetPasswordRepositoryInterface
{
    public function __construct(ResetPassword $model)
    {
        parent::__construct($model);
    }

    public function findPendingRequest($mobile)
    {
        return $this->model->where("mobile", $mobile)->pending()->unExpire()->first();
    }

    public function findInProgressRequest($mobile)
    {
        return $this->model->where("mobile", $mobile)->inProgress()->unExpire()->first();
     }

    public function setVerificationCode($username , $userId, $code)
    {
        return $this->create(["username" => $username,"user_id" => $userId, "status" => ResetPasswordStatus::Pending->value, "code" => $code, "expire_at" => Carbon::now()->addMinutes(config("settings.reset_password.code_expire_minutes"))]);
    }

    public function setInProgress($id)
    {
        $this->update($this->findOrFail($id), ["status" => ResetPasswordStatus::InProgress->value]);
    }

    public function setCompleted($id)
    {
        $this->update($this->findOrFail($id), ["status" => ResetPasswordStatus::Completed->value]);
    }
}
