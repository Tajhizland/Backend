<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\ImageSortRequest;
use App\Http\Requests\V1\Admin\Option\UpdateProductOptionRequest;
use App\Http\Requests\V1\Admin\Product\ColorFastUpdateRequest;
use App\Http\Requests\V1\Admin\Product\GroupChangePriceRequest;
use App\Http\Requests\V1\Admin\Product\ProductColorRequest;
use App\Http\Requests\V1\Admin\Product\ProductFilterRequest;
use App\Http\Requests\V1\Admin\Product\ProductImageRequest;
use App\Http\Requests\V1\Admin\Product\ProductOptionRequest;
use App\Http\Requests\V1\Admin\Product\SearchListRequest;
use App\Http\Requests\V1\Admin\Product\SetProductVideosRequest;
use App\Http\Requests\V1\Admin\Product\SetVideoRequest;
use App\Http\Requests\V1\Admin\Product\StoreProductRequest;
use App\Http\Requests\V1\Admin\Product\UpdateProductRequest;
use App\Http\Resources\V1\Filter\FilterCollection;
use App\Http\Resources\V1\Option\OptionCollection;
use App\Http\Resources\V1\OptionItem\OptionItemCollection;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Product\ProductResource;
use App\Http\Resources\V1\ProductColor\ProductColorCollection;
use App\Http\Resources\V1\ProductImage\ProductImageCollection;
use App\Http\Resources\V1\ProductVideo\ProductVideoCollection;
use App\Services\Filter\FilterServiceInterface;
use App\Services\Option\OptionServiceInterface;
use App\Services\Product\ProductServiceInterface;
use App\Services\ProductColor\ProductColorServiceInterface;
use App\Services\ProductImage\ProductImageServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class ProductController extends Controller
{
    public function __construct
    (
        private ProductServiceInterface      $productService,
        private OptionServiceInterface       $optionService,
        private FilterServiceInterface       $filterService,
        private ProductColorServiceInterface $productColorService,
        private ProductImageServiceInterface $productImageService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new ProductCollection($this->productService->dataTable()));
    }

    public function stockProductDataTable()
    {
        return $this->dataResponseCollection(new ProductCollection($this->productService->stockDataTable()));
    }

    public function hasDiscountDataTable()
    {
        return $this->dataResponseCollection(new ProductCollection($this->productService->hasDiscountDataTable()));
    }

    public function hasLimitDataTable()
    {
        return $this->dataResponseCollection(new ProductCollection($this->productService->hasLimitDataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new ProductResource($this->productService->findById($id)));
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->storeProduct($request->get("name"), $request->get("url"), $request->get("description"), $request->get("study"), $request->get("status"), $request->get("categoryId"), $request->get("brand_id"), $request->get("meta_title"), $request->get("meta_description"), $request->get("guaranty_id"), $request->get("guaranty_time"), $request->get("review"), $request->get("type"), $request->get("is_stock", 0), $request->get("testing_time"), $request->get("stock_of"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.product")]));
    }

    public function update(UpdateProductRequest $request)
    {
        $this->productService->updateProduct($request->get("id"), $request->get("name"), $request->get("url"), $request->get("description"), $request->get("study"), $request->get("status"), $request->get("categoryId"), $request->get("brand_id"), $request->get("meta_title"), $request->get("meta_description"), $request->get("guaranty_id"), $request->get("guaranty_time"), $request->get("review"), $request->get("type"), $request->get("is_stock", 0), $request->get("testing_time"), $request->get("stock_of"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.product")]));
    }

    public function getFilter($id)
    {
        return $this->dataResponseCollection(new FilterCollection($this->filterService->getByProductId($id)));

    }

    public function getOption($id)
    {
        return $this->dataResponseCollection(new OptionItemCollection($this->optionService->getByProductId($id)));
    }

    public function getColor($id)
    {
        return $this->dataResponseCollection(new ProductColorCollection($this->productColorService->getByProductId($id)));
    }

    public function getImage($id)
    {
        return $this->dataResponseCollection(new ProductImageCollection($this->productImageService->getByProductId($id)));
    }

    public function setFilter(ProductFilterRequest $request)
    {
        $this->filterService->setFilterToProduct($request->get("product_id"), $request->get("filter"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.filter")]));
    }

    public function setOption(ProductOptionRequest $request)
    {
        $this->optionService->setOptionToProduct($request->get("product_id"), $request->get("option"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.option")]));
    }

    public function setColor(ProductColorRequest $request)
    {
        $this->productColorService->setProductColor($request->get("product_id"), $request->get("color"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.color")]));
    }

    public function setImage(ProductImageRequest $request)
    {
        $this->productImageService->upload($request->get("product_id"), $request->file("image"));
        return $this->successResponse(Lang::get("action.upload", ["attr" => Lang::get("attr.file")]));
    }

    public function removeImage($id)
    {
        $this->productImageService->remove($id);
        return $this->successResponse(Lang::get("action.remove", ["attr" => Lang::get("attr.file")]));
    }

    public function setVideo(SetVideoRequest $request)
    {
        $this->productService->setVideo($request->get("productId"), $request->get("vlogId"), $request->get("type"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.file")]));
    }

    public function setVideo2(SetProductVideosRequest $request)
    {
        $this->productService->setVideo2($request->get("product_id"), $request->get("vlogId"), $request->get("title"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.file")]));
    }

    public function getVideo($id)
    {
        $response = $this->productService->getVideo($id);
        return $this->dataResponseCollection(new ProductVideoCollection($response));
    }

    public function searchList(SearchListRequest $request)
    {
        $response = $this->productService->searchList($request->get("categoryId"), $request->get("brandId"));
        return $this->dataResponseCollection(new ProductCollection($response));
    }

    public function deleteVideo($id)
    {
        $this->productService->deleteVideo($id);
        return $this->successResponse(Lang::get("action.remove", ["attr" => Lang::get("attr.file")]));
    }

    public function colorFastUpdate(ColorFastUpdateRequest $request)
    {
        $this->productColorService->colorFastUpdate($request->get("color"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.color")]));
    }

    public function sortImage(ImageSortRequest $request)
    {
        $this->productImageService->sort($request->get("image"));
        return $this->successResponse(Lang::get("action.sort", ["attr" => Lang::get("attr.image")]));

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
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.option")]));
    }

    public function groupChange(GroupChangePriceRequest $request)
    {
        $this->productService->groupChangePrice($request->get('ids'), $request->get('action'), $request->get('percent'));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.price")]));
    }

}
