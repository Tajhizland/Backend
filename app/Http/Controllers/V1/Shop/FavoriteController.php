<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Product\Favorite\ChangeFavoriteRequest;
use App\Services\Favorite\FavoriteServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Http\Resources\Product\ProductResource;

class FavoriteController extends Controller
{
    public function __construct
    (
        private FavoriteServiceInterface $favoriteService
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
        return $this->successResponse(Lang::get("action.add_to",["attr"=>Lang::get("attr.product") ,"to"=>Lang::get("attr.favorite")]));
    }

    public function removeProduct(ChangeFavoriteRequest $request)
    {
        $this->favoriteService->removeProduct($request->get("productId"), Auth::user()->id);
        return $this->successResponse(Lang::get("action.remove_from",["attr"=>Lang::get("attr.product") ,"from"=>Lang::get("attr.favorite")]));
     }
}
