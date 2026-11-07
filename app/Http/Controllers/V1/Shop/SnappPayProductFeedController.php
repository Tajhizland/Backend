<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\SnappPay\ProductFeedRequest;
use App\Http\Resources\SnappPay\ProductFeedResource;
use App\Services\SnappPay\ProductFeedService;
use Illuminate\Http\JsonResponse;

class SnappPayProductFeedController extends Controller
{
    public function __construct(private readonly ProductFeedService $feed) {}

    public function index(ProductFeedRequest $request): JsonResponse
    {
        $input = $request->validated();
        if (isset($input['id']) || isset($input['slug'])) {
            $product = isset($input['id'])
                ? $this->feed->findById((int) $input['id'])
                : $this->feed->findBySlug($input['slug']);

            return $this->productResponse($request, $product);
        }

        $page = (int) ($input['page'] ?? 1);
        $size = (int) ($input['size'] ?? 100);
        $products = $this->feed->listing($page, $size);

        // A dedicated external feed: no internal success/result API envelope.
        return response()->json([
            'products' => ProductFeedResource::collection($products)->resolve($request),
            'total' => $products->total(),
            'page' => $products->currentPage(),
            'size' => $products->perPage(),
            'total_pages' => $products->lastPage(),
        ])->header('Cache-Control', 'no-store');
    }

    public function show(ProductFeedRequest $request, int $id): JsonResponse
    {
        return $this->productResponse($request, $this->feed->findById($id));
    }

    private function productResponse(ProductFeedRequest $request, $product): JsonResponse
    {
        return response()->json((new ProductFeedResource($product))->resolve($request))
            ->header('Cache-Control', 'no-store');
    }
}
