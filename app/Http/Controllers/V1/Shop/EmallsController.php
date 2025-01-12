<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Product\EmallsCollection;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;

class EmallsController extends Controller
{
    public function __construct
    (
        private ProductServiceInterface $productService
    )
    {
    }

    public function list(Request $request)
    {
        $itemPerPage = $request->item_per_page;
        $data = $this->productService->customPaginate($itemPerPage);
        return (new EmallsCollection($data))->jsonSerialize();
    }
}
