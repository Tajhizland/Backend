<?php

namespace App\Policies;

use App\Enums\OnHoldOrderStatus;
use App\Models\OnHoldOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OnHoldOrderPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, OnHoldOrder $onHoldOrder): bool
    {
        return ($onHoldOrder->order->user_id==$user->id && $onHoldOrder->status==OnHoldOrderStatus::Pending->value);
    }
    public function update(User $user, OnHoldOrder $onHoldOrder): bool
    {
        return ($onHoldOrder->status==OnHoldOrderStatus::Pending->value);
    }
}
