<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Requests\Shop\Emalls\EmallsListRequest;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;

class EmallsController extends Controller
{
    public function __construct
    (
        private readonly ProductServiceInterface $productService
    )
    {
    }

    public function list(EmallsListRequest $request)
    {
        $data = $this->productService->customPaginate($request->validated()["item_per_page"] ?? null);

        if ($data instanceof LengthAwarePaginator) {
            return [
                'products' => $data->items(),
                'pages_count' => $data->lastPage(),
                'item_per_page' => $data->perPage(),
                'total_items' => $data->total(),
            ];
        }

        return ['products' => $data];
    }
}
