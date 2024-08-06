<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Product\Favorite\ChangeFavoriteRequest;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Services\Favorite\FavoriteServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

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
        return $this->dataResponse(new ProductCollection($response));
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
