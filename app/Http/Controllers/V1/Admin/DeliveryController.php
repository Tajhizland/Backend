<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Delivery\DeliveryServiceInterface;

class DeliveryController extends Controller
{
    public function __construct
    (
        private DeliveryServiceInterface $deliveryService
    )
    {
    }
}
