<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Gateway\GatewayServiceInterface;

class GatewayController extends Controller
{
    public function __construct
    (
        private GatewayServiceInterface $gatewayService
    )
    {
    }

    public function dataTable()
    {

    }

    public function findById($id)
    {

    }

    public function store()
    {

    }

    public function update()
    {

    }
}
