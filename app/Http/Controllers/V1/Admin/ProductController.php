<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GroupChangeDigipayRequest;
use App\Http\Requests\Admin\GroupChangeSnappayRequest;
use App\Http\Requests\Admin\ImageSortRequest;
use App\Http\Requests\Admin\Option\UpdateProductOptionRequest;
use App\Http\Requests\Admin\Product\ColorFastUpdateRequest;
use App\Http\Requests\Admin\Product\GroupChangePriceRequest;
use App\Http\Requests\Admin\Product\GroupChangePercentRequest;
use App\Http\Requests\Admin\Product\GroupChangeStatusRequest;
use App\Http\Requests\Admin\Product\GroupChangeStockRequest;
use App\Http\Requests\Admin\Product\ProductColorRequest;
use App\Http\Requests\Admin\Product\ProductFilterRequest;
use App\Http\Requests\Admin\Product\ProductImageRequest;
use App\Http\Requests\Admin\Product\ProductOptionRequest;
use App\Http\Requests\Admin\Product\SetImageColorRequest;
use App\Http\Requests\Admin\Product\SearchListRequest;
use App\Http\Requests\Admin\Product\SetProductVideosRequest;
use App\Http\Requests\Admin\Product\SetVideoRequest;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Services\Filter\FilterServiceInterface;
use App\Services\Option\OptionServiceInterface;
use App\Services\Product\ProductServiceInterface;
use App\Services\ProductColor\ProductColorServiceInterface;
use App\Services\ProductImage\ProductImageServiceInterface;
use Illuminate\Http\Request;
use App\Http\Resources\ProductImage\ProductImageResource;
use App\Http\Resources\Filter\FilterResource;
use App\Http\Resources\ProductVideo\ProductVideoResource;
use App\Http\Resources\OptionItem\OptionItemResource;
use App\Http\Resources\ProductColor\ProductColorResource;

class ProductController extends Controller
{
    public function __construct
    (
        private readonly ProductServiceInterface      $productService,
        private readonly OptionServiceInterface       $optionService,
        private readonly FilterServiceInterface       $filterService,
        private readonly ProductColorServiceInterface $productColorService,
        private readonly ProductImageServiceInterface $productImageService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(ProductResource::collection($this->productService->dataTable()));
    }

    public function stockProductDataTable()
    {
        return $this->dataResponseCollection(ProductResource::collection($this->productService->stockDataTable()));
    }

    public function hasDiscountDataTable()
    {
        return $this->dataResponseCollection(ProductResource::collection($this->productService->hasDiscountDataTable()));
    }

    public function hasLimitDataTable()
    {
        return $this->dataResponseCollection(ProductResource::collection($this->productService->hasLimitDataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new ProductResource($this->productService->findById($id)));
    }

 public function store(StoreProductRequest $request)
    {
        $this->productService->storeProduct($request->get("name"), $request->get("url"), $request->get("description"), $request->get("study"), $request->get("status"), $request->get("categoryId"), $request->get("brand_id"), $request->get("meta_title"), $request->get("meta_description"), $request->get("guaranty_id"), $request->get("guaranty_time"), $request->get("review"), $request->get("type"), $request->get("is_stock", 0), $request->get("testing_time"), $request->get("stock_of"), $request->get("length"), $request->get("width"), $request->get("height"), $request->get("weight"), $request->get("use_packet"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.product")]));
    }

    public function update(UpdateProductRequest $request)
    {
        $this->productService->updateProduct($request->get("id"), $request->get("name"), $request->get("url"), $request->get("description"), $request->get("study"), $request->get("status"), $request->get("categoryId"), $request->get("brand_id"), $request->get("meta_title"), $request->get("meta_description"), $request->get("guaranty_id"), $request->get("guaranty_time"), $request->get("review"), $request->get("type"), $request->get("is_stock", 0), $request->get("testing_time"), $request->get("stock_of"), $request->get("length"), $request->get("width"), $request->get("height"), $request->get("weight"), $request->get("use_packet"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.product")]));
    }

    public function getFilter($id)
    {
        return $this->dataResponseCollection(FilterResource::collection($this->filterService->getByProductId($id)));

    }

    public function getOption($id)
    {
        return $this->dataResponseCollection(OptionItemResource::collection($this->optionService->getByProductId($id)));
    }

    public function getColor($id)
    {
        return $this->dataResponseCollection(ProductColorResource::collection($this->productColorService->getByProductId($id)));
    }

    public function getImage($id)
    {
        return $this->dataResponseCollection(ProductImageResource::collection($this->productImageService->getByProductId($id)));
    }

    public function setFilter(ProductFilterRequest $request)
    {
        $this->filterService->setFilterToProduct($request->get("product_id"), $request->get("filter"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.filter")]));
    }

    public function setOption(ProductOptionRequest $request)
    {
        $this->optionService->setOptionToProduct($request->get("product_id"), $request->get("option"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.option")]));
    }

    public function setColor(ProductColorRequest $request)
    {
        $this->productColorService->setProductColor($request->get("product_id"), $request->get("color"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.color")]));
    }

    public function setImage(ProductImageRequest $request)
    {
        $this->productImageService->upload($request->get("product_id"), $request->file("image"));
        return $this->successResponse(__("action.upload", ["attr" => __("attr.file")]));
    }

    public function removeImage($id)
    {
        $this->productImageService->remove($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.file")]));
    }

    public function setVideo(SetVideoRequest $request)
    {
        $this->productService->setVideo($request->get("productId"), $request->get("vlogId"), $request->get("type"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.file")]));
    }

    public function setVideo2(SetProductVideosRequest $request)
    {
        $this->productService->setVideo2($request->get("product_id"), $request->get("vlogId"), $request->get("title"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.file")]));
    }

    public function getVideo($id)
    {
        $response = $this->productService->getVideo($id);
        return $this->dataResponseCollection(ProductVideoResource::collection($response));
    }

    public function searchList(SearchListRequest $request)
    {
        $response = $this->productService->searchList($request->get("categoryId"), $request->get("brandId"), $request->get("searchQuery"), $request->get("discountId"));
        return $this->dataResponseCollection(ProductResource::collection($response));
    }

    public function deleteVideo($id)
    {
        $this->productService->deleteVideo($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.file")]));
    }

    public function colorFastUpdate(ColorFastUpdateRequest $request)
    {
        $this->productColorService->colorFastUpdate($request->get("color"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.color")]));
    }

    public function sortImage(ImageSortRequest $request)
    {
        $this->productImageService->sort($request->get("image"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.image")]));

    }

    public function setImageColor(SetImageColorRequest $request)
    {
        $this->productImageService->setColor($request->get("image"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.image")]));
    }

    public function updateProductOption(UpdateProductOptionRequest $request)
    {
        $options = $request->get('options');

        foreach ($options as $optionData) {
            $this->optionService->updateProductOption(
                $optionData['id'] ?? null,
                $optionData['productId'],
                $optionData['value'] ?? null,
                $optionData['option_item_id']
            );
        }
        return $this->successResponse(__("action.update", ["attr" => __("attr.option")]));
    }

    public function groupChange(GroupChangePriceRequest $request)
    {
        $this->productService->groupChangePrice($request->get('ids'), $request->get('action'), $request->get('percent'));
        return $this->successResponse(__("action.update", ["attr" => __("attr.price")]));
    }

    public function groupChangePercent(GroupChangePercentRequest $request)
    {
        $this->productService->groupChangeDigipayPercent($request->get('ids'), $request->get('percent'));
        return $this->successResponse(__("action.update", ["attr" => __("attr.product")]));
    }

    public function groupChangeStock(GroupChangeStockRequest $request)
    {
        $this->productService->groupChangeStock($request->get('ids'), $request->get('stock'));
        return $this->successResponse(__("action.update", ["attr" => __("attr.product")]));
    }

    public function groupChangeStatus(GroupChangeStatusRequest $request)
    {
        $this->productService->groupChangeStatus($request->get('ids'), $request->get('status'));
        return $this->successResponse(__("action.update", ["attr" => __("attr.product")]));
    }
    public function groupChangeDigipay(GroupChangeDigipayRequest $request)
    {
        $this->productService->groupChangeDigipay($request->get('ids'), $request->get('digipay'));
        return $this->successResponse(__("action.update", ["attr" => __("attr.product")]));
    }
    public function groupChangeSnappay(GroupChangeSnappayRequest $request)
    {
        $this->productService->groupChangeSnappay($request->get('ids'), $request->get('snappay'));
        return $this->successResponse(__("action.update", ["attr" => __("attr.product")]));
    }
}
