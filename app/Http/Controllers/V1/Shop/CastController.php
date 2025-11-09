<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Cast\CastCollection;
use App\Services\Cast\CastServiceInterface;

class CastController extends Controller
{
    public function __construct
    (
        private CastServiceInterface $castService
    )
    {
    }

    public function index()
    {
        $response = $this->castService->paginated();
        return $this->dataResponseCollection(new CastCollection($response));
    }
}
