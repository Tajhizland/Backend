<?php

namespace App\Repositories\Gateway;

use App\Models\Gateway;
use App\Repositories\Base\BaseRepositoryInterface;

interface GatewayRepositoryInterface extends  BaseRepositoryInterface
{
    public function dataTable();
    public function activeCountExceptThis($id);
    public function findActiveGateway();

    public function createGateway( $name,$status, $description);

    public function updateGateway(Gateway $gateway, $name, $status, $description);
}
