<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Product\Favorite\ChangeFavoriteRequest;
use App\Services\Favorite\FavoriteServiceInterface;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Product\ProductResource;

class FavoriteController extends Controller
{
    public function __construct
    (
        private readonly FavoriteServiceInterface $favoriteService
    )
    {
    }
    public function index()
    {
        $response = $this->favoriteService->showProducts(Auth::user()->id);
        return $this->dataResponseCollection(ProductResource::collection($response));
    }

    public function addProduct(ChangeFavoriteRequest $request)
    {
        $this->favoriteService->addProduct($request->get("productId"), Auth::user()->id);
        return $this->successResponse(__("action.add_to",["attr"=>__("attr.product") ,"to"=>__("attr.favorite")]));
    }

    public function removeProduct(ChangeFavoriteRequest $request)
    {
        $this->favoriteService->removeProduct($request->get("productId"), Auth::user()->id);
        return $this->successResponse(__("action.remove_from",["attr"=>__("attr.product") ,"from"=>__("attr.favorite")]));
     }
}
