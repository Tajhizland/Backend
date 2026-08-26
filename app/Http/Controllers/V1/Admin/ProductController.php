<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Product\ProductGroupDigipayDto;
use App\DTOs\Product\ProductGroupPercentDto;
use App\DTOs\Product\ProductGroupPriceDto;
use App\DTOs\Product\ProductGroupSnappayDto;
use App\DTOs\Product\ProductGroupStatusDto;
use App\DTOs\Product\ProductGroupStockDto;
use App\DTOs\Product\ProductSearchListDto;
use App\DTOs\Product\ProductSetFilterDto;
use App\DTOs\Product\ProductSetOptionDto;
use App\DTOs\Product\ProductSetVideo2Dto;
use App\DTOs\Product\ProductSetVideoDto;
use App\DTOs\Product\ProductStoreDto;
use App\DTOs\Product\ProductUpdateDto;
use App\DTOs\ProductColor\ProductColorFastUpdateDto;
use App\DTOs\ProductColor\ProductColorSetDto;
use App\DTOs\ProductImage\ProductImageSetColorDto;
use App\DTOs\ProductImage\ProductImageSortDto;
use App\DTOs\ProductImage\ProductImageUploadDto;
use App\DTOs\Option\ProductOptionUpdateDto;
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

    public function show($id)
    {
        return $this->dataResponse(new ProductResource($this->productService->find($id)));
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->storeProduct(new ProductStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.product")]));
    }

    public function update($id, UpdateProductRequest $request)
    {
        $this->productService->updateProduct(new ProductUpdateDto($id, ...$request->validated()));
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
        $this->filterService->setFilterToProduct(new ProductSetFilterDto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.filter")]));
    }

    public function setOption(ProductOptionRequest $request)
    {
        $this->optionService->setOptionToProduct(new ProductSetOptionDto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.option")]));
    }

    public function setColor(ProductColorRequest $request)
    {
        $this->productColorService->setProductColor(new ProductColorSetDto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.color")]));
    }

    public function setImage(ProductImageRequest $request)
    {
        $this->productImageService->upload(new ProductImageUploadDto(...$request->validated()));
        return $this->successResponse(__("action.upload", ["attr" => __("attr.file")]));
    }

    public function removeImage($id)
    {
        $this->productImageService->remove($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.file")]));
    }

    public function setVideo(SetVideoRequest $request)
    {
        $this->productService->setVideo(new ProductSetVideoDto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.file")]));
    }

    public function setVideo2(SetProductVideosRequest $request)
    {
        $this->productService->setVideo2(new ProductSetVideo2Dto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.file")]));
    }

    public function getVideo($id)
    {
        $response = $this->productService->getVideo($id);
        return $this->dataResponseCollection(ProductVideoResource::collection($response));
    }

    public function searchList(SearchListRequest $request)
    {
        $response = $this->productService->searchList(new ProductSearchListDto(...$request->validated()));
        return $this->dataResponseCollection(ProductResource::collection($response));
    }

    public function deleteVideo($id)
    {
        $this->productService->deleteVideo($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.file")]));
    }

    public function colorFastUpdate(ColorFastUpdateRequest $request)
    {
        $this->productColorService->colorFastUpdate(new ProductColorFastUpdateDto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.color")]));
    }

    public function sortImage(ImageSortRequest $request)
    {
        $this->productImageService->sort(new ProductImageSortDto(...$request->validated()));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.image")]));

    }

    public function setImageColor(SetImageColorRequest $request)
    {
        $this->productImageService->setColor(new ProductImageSetColorDto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.image")]));
    }

    public function updateProductOption(UpdateProductOptionRequest $request)
    {
        $options = (new ProductOptionUpdateDto(...$request->validated()))->options;

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
        $this->productService->groupChangePrice(new ProductGroupPriceDto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.price")]));
    }

    public function groupChangePercent(GroupChangePercentRequest $request)
    {
        $this->productService->groupChangeDigipayPercent(new ProductGroupPercentDto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.product")]));
    }

    public function groupChangeStock(GroupChangeStockRequest $request)
    {
        $this->productService->groupChangeStock(new ProductGroupStockDto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.product")]));
    }

    public function groupChangeStatus(GroupChangeStatusRequest $request)
    {
        $this->productService->groupChangeStatus(new ProductGroupStatusDto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.product")]));
    }
    public function groupChangeDigipay(GroupChangeDigipayRequest $request)
    {
        $this->productService->groupChangeDigipay(new ProductGroupDigipayDto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.product")]));
    }
    public function groupChangeSnappay(GroupChangeSnappayRequest $request)
    {
        $this->productService->groupChangeSnappay(new ProductGroupSnappayDto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.product")]));
    }
}
