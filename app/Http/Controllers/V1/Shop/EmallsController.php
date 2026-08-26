<?php

namespace App\Http\Controllers\V1\Shop;

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

    public function list(Request $request)
    {
        $data = $this->productService->customPaginate($request->item_per_page);

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
