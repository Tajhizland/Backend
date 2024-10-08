<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Delivery\DeliveryCollection;
use App\Services\Delivery\DeliveryServiceInterface;

class DeliveryController extends Controller
{
    public function __construct
    (private DeliveryServiceInterface $deliveryService)
    {
    }

    public function getActives()
    {
        return $this->dataResponseCollection(new DeliveryCollection($this->deliveryService->getActives()));
    }
}
