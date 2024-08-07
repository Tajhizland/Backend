<?php

namespace App\Policies;

use App\Enums\ReturnedStatus;
use App\Models\Returned;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReturnedPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Returned $returned): bool
    {
        return  $returned->status==ReturnedStatus::Pending->value;
    }
    public function isPending(Returned $returned): bool
    {
        return  $returned->status==ReturnedStatus::Pending->value;
    }
}
