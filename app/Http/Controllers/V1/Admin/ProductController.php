<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Product\ProductColorRequest;
use App\Http\Requests\V1\Admin\Product\ProductFileRequest;
use App\Http\Requests\V1\Admin\Product\ProductFilterRequest;
use App\Http\Requests\V1\Admin\Product\ProductImageRequest;
use App\Http\Requests\V1\Admin\Product\ProductOptionRequest;
use App\Http\Requests\V1\Admin\Product\StoreProductRequest;
use App\Http\Requests\V1\Admin\Product\UpdateProductRequest;
use App\Http\Resources\V1\Filter\FilterCollection;
use App\Http\Resources\V1\Option\OptionCollection;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Product\ProductResource;
use App\Http\Resources\V1\ProductColor\ProductColorCollection;
use App\Services\FileManager\FileManagerServiceInterface;
use App\Services\Filter\FilterServiceInterface;
use App\Services\Option\OptionServiceInterface;
use App\Services\Product\ProductServiceInterface;
use App\Services\ProductColor\ProductColorServiceInterface;
use App\Services\ProductImage\ProductImageServiceInterface;
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
        private FileManagerServiceInterface  $fileManagerService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new ProductCollection($this->productService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new ProductResource($this->productService->findById($id)));
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->storeProduct($request->get("name"), $request->get("url"), $request->get("description"), $request->get("study"), $request->get("status"), $request->get("category_id"), $request->get("brand_id"), $request->get("meta_title"), $request->get("meta_description"), $request->get("color"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.product")]));
    }

    public function update(UpdateProductRequest $request)
    {
        $this->productService->updateProduct($request->get("id"), $request->get("name"), $request->get("url"), $request->get("description"), $request->get("status"), $request->get("study"), $request->get("category_id"), $request->get("brand_id"), $request->get("meta_title"), $request->get("meta_description"), $request->get("color"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.product")]));
    }

    public function getFilter($id)
    {
        return $this->dataResponseCollection(new FilterCollection($this->filterService->getByProductId($id)));

    }

    public function getOption($id)
    {
        return $this->dataResponseCollection(new OptionCollection($this->optionService->getByProductId($id)));
    }

    public function getColor($id)
    {
        return $this->dataResponseCollection(new ProductColorCollection($this->productColorService->getByProductId($id)));
    }

    public function getImage($id)
    {
        return $this->dataResponseCollection($this->productImageService->getByProductId($id));
    }

    public function getFiles($id)
    {
        return $this->dataResponseCollection($this->fileManagerService->geyByModelId($id, "product"));
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
        $this->productImageService->create($request->get("product_id"), $request->file("image"));
        return $this->successResponse(Lang::get("action.upload", ["attr" => Lang::get("attr.file")]));
    }

    public function setFile(ProductFileRequest $request)
    {
        $this->fileManagerService->upload($request->file("file"), "product", "product", $request->get("product_id"));
        return $this->successResponse(Lang::get("action.upload", ["attr" => Lang::get("attr.file")]));
    }
}
