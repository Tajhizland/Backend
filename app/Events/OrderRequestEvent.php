<?php

namespace App\Events;

use App\Models\OnHoldOrder;
use Illuminate\Foundation\Events\Dispatchable;

class OrderRequestEvent
{
    use Dispatchable;

    public OnHoldOrder $onHoldOrder;

    public function __construct(OnHoldOrder $onHoldOrder)
    {
        $this->onHoldOrder = $onHoldOrder;
    }
}
